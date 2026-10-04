<?php

declare(strict_types=1);

/**
 * IDE Helper file for Swap Hub.
 * Provides type hints and autocomplete definitions for IDEs (VS Code, Intelephense, PhpStorm, Cursor).
 * This file is never loaded at runtime by Laravel.
 */

namespace Illuminate\Contracts\Auth {
    /**
     * @mixin \App\Models\User
     */
    interface Authenticatable {}
}

namespace Illuminate\Support\Facades {
    /**
     * @method static \App\Models\User|null user()
     */
    class Auth {}
}

namespace Illuminate\Http {
    class Request {
        /**
         * @param string|null $guard
         * @return \App\Models\User|null
         */
        public function user($guard = null) {}
    }
}

namespace {
    /**
     * @param string|null $guard
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\App\Models\User|null
     */
    function auth($guard = null) {}
}
