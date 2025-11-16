import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
   safelist: [
    'bg-green-100', 'dark:bg-green-900', 'text-green-800', 'dark:text-green-300',
    'bg-yellow-100', 'dark:bg-yellow-900', 'text-yellow-800', 'dark:text-yellow-300',
    'bg-red-100', 'dark:bg-red-900', 'text-red-800', 'dark:text-red-300',
    'bg-indigo-600', 'hover:bg-indigo-700',
    'bg-yellow-600', 'hover:bg-yellow-700',
    'bg-red-600', 'hover:bg-red-700',
    'bg-green-600', 'hover:bg-green-700',
  ],
}
