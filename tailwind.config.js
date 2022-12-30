const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './storage/framework/views/*.php',
        './vendor/filament/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.blue,
                success: colors.emerald,
                warning: colors.yellow,
                gray: {
                    850: '#202023',
                    ...colors.zinc
                },
                purple: colors.violet,
                blue: colors.cyan,
                green: colors.emerald,
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Lexend', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['2rem', { lineHeight: '2.5rem' }],
                '4xl': ['2.5rem', { lineHeight: '3.5rem' }],
                '5xl': ['3rem', { lineHeight: '3.5rem' }],
                '6xl': ['3.75rem', { lineHeight: '1' }],
                '7xl': ['4.5rem', { lineHeight: '1.1' }],
                '8xl': ['6rem', { lineHeight: '1' }],
                '9xl': ['8rem', { lineHeight: '1' }],
            },
        },
    },

    plugins: [
        require('@tailwindcss/container-queries'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ],
}
