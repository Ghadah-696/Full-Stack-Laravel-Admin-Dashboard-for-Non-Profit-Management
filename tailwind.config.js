import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // 💡 أضيفي مسار ملفات JS (إذا كانت تستخدم فئات Tailwind)
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    colors: {
                // اللون الرئيسي الجديد
                'primary': '#38b6ff', 
                // نسخة أغمق قليلاً لاستخدامها في التمرير (Hover)
                'primary-dark': '#2e96d9', 
            },

    plugins: [forms],
};
