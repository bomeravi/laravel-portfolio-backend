<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class DemoUserWebMiddleware
{
    /**
     * Simulated routes with their corresponding Form Request classes
     */
    protected $simulatedRoutes = [
        // 'blogs.store' => \App\Http\Requests\CreateBlogRequest::class,
        // 'blogs.update' => \App\Http\Requests\UpdateBlogRequest::class,
        // 'blogs.destroy' => null,
        // 'categories.store' => \App\Http\Requests\CategoryRequest::class,
        // 'categories.update' => \App\Http\Requests\CategoryRequest::class,
        // 'categories.destroy' => null,
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Check if user is demo user
        if ($user && $user->email === 'demo@example.com') {
            // Allow only logout route
            if ($request->routeIs('logout') ||
                $request->is('logout') ||
                Str::contains($request->path(), 'logout')) {
                return $next($request);
            }

            // Block all POST/PUT/PATCH/DELETE requests for demo user
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                // Try to validate with Form Request or general validation
                $validationResult = $this->validateDemoRequest($request);

                if ($validationResult['success']) {
                    // Validation passed - redirect to the intended route with success message
                    return redirect()
                        ->to($request->url())
                        ->with('success', 'Validation passed, but demo users cannot perform this action. Only logout is allowed.')
                        ->with('validated_data', $validationResult['data']);
                } else {
                    // Validation failed - redirect back with errors
                    return redirect()
                        ->back()
                        ->withErrors($validationResult['errors'])
                        ->withInput();
                }
            }
        }

        return $next($request);
    }

    /**
     * Validate demo user request
     */
    protected function validateDemoRequest(Request $request): array
    {
        try {
            // Try to find and use Form Request for this route
            $formRequestClass = $this->findFormRequestClass($request);

            if ($formRequestClass && class_exists($formRequestClass)) {
                $formRequest = app($formRequestClass);
                $rules = $formRequest->rules();

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return [
                        'success' => false,
                        'errors' => $validator->errors()->toArray(),
                        'data' => null
                    ];
                }

                return [
                    'success' => true,
                    'errors' => null,
                    'data' => $validator->validated()
                ];
            }

            // Use general validation if no Form Request found
            return $this->applyGeneralValidation($request);

        } catch (\Exception $e) {
            // If Form Request validation fails, use general validation
            return $this->applyGeneralValidation($request);
        }
    }

    /**
     * Find Form Request class for current route
     */
    protected function findFormRequestClass(Request $request): ?string
    {
        $route = $request->route();
        if (!$route) {
            return null;
        }

        $action = $route->getAction();
        $controller = $action['controller'] ?? null;

        if (!$controller || !Str::contains($controller, '@')) {
            return null;
        }

        [$controllerClass, $method] = explode('@', $controller);

        // Try to determine Form Request class name based on controller and method
        $possibleFormRequests = $this->getPossibleFormRequestNames($controllerClass, $method, $request);

        foreach ($possibleFormRequests as $formRequestClass) {
            if (class_exists($formRequestClass)) {
                return $formRequestClass;
            }
        }

        return null;
    }

    /**
     * Get possible Form Request class names
     */
    protected function getPossibleFormRequestNames(string $controller, string $method, Request $request): array
    {
        $controllerBaseName = class_basename($controller);
        $resourceName = str_replace('Controller', '', $controllerBaseName);

        $possibleNames = [];

        // Based on method name
        $methodMap = [
            'store' => ['Create', 'Store'],
            'update' => ['Update'],
            'destroy' => ['Delete', 'Destroy'],
        ];

        if (isset($methodMap[$method])) {
            foreach ($methodMap[$method] as $prefix) {
                $possibleNames[] = "App\\Http\\Requests\\{$prefix}{$resourceName}Request";
                $possibleNames[] = "App\\Http\\Requests\\{$resourceName}\\{$prefix}{$resourceName}Request";
            }
        }

        // Generic patterns
        $possibleNames[] = "App\\Http\\Requests\\" . Str::studly($method) . "{$resourceName}Request";

        return $possibleNames;
    }

    /**
     * Apply general validation rules
     */
    protected function applyGeneralValidation(Request $request): array
    {
        $data = $request->all();

        if (empty($data)) {
            return [
                'success' => true,
                'errors' => null,
                'data' => []
            ];
        }

        $rules = [];

        // Build validation rules dynamically based on present fields
        foreach ($data as $key => $value) {
            $rules[$key] = $this->getValidationRule($key, $value);
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()->toArray(),
                'data' => null
            ];
        }

        return [
            'success' => true,
            'errors' => null,
            'data' => $validator->validated()
        ];
    }

    /**
     * Get validation rule for a field
     */
    protected function getValidationRule(string $field, $value): array
    {
        // Common field validation rules
        $rules = match($field) {
            'email' => ['required', 'email'],
            'name' => ['required', 'string', 'min:4'],
            'username' => ['required', 'string', 'min:4'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'image' => ['nullable', 'image', 'max:4096'], // 4MB in KB
            'photo' => ['nullable', 'image', 'max:4096'],
            'avatar' => ['nullable', 'image', 'max:4096'],
            'title' => ['required', 'string', 'min:4'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'url' => ['nullable', 'url'],
            'date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,inactive,pending,draft,published'],
            default => $this->inferValidationRule($field, $value)
        };

        return $rules;
    }

    /**
     * Infer validation rule based on field name pattern or value type
     */
    protected function inferValidationRule(string $field, $value): array
    {
        // Image fields
        if (str_ends_with($field, '_image') || str_ends_with($field, '_photo')) {
            return ['nullable', 'image', 'max:4096'];
        }

        // Email fields
        if (str_contains($field, 'email')) {
            return ['required', 'email'];
        }

        // Name fields - require at least 4 characters
        if (str_contains($field, 'name')) {
            return ['required', 'string', 'min:4'];
        }

        // ID fields
        if (str_ends_with($field, '_id') || $field === 'id') {
            return ['nullable', 'integer'];
        }

        // Date fields
        if (str_ends_with($field, '_at') || str_contains($field, 'date')) {
            return ['nullable', 'date'];
        }

        // Boolean fields
        if (str_starts_with($field, 'is_') || str_starts_with($field, 'has_')) {
            return ['nullable', 'boolean'];
        }

        // Default: string with max length
        return ['nullable', 'string', 'max:255'];
    }
}