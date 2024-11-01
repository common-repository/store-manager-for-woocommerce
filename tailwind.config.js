/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [ './backend/views/src/**/*.{js,jsx,ts,tsx}' ],
	theme: {
		fontFamily: {
			sans: [ '"Nunito Sans"', 'sans-serif' ],
		},
		extend: {
			colors: {
				primary: {
					DEFAULT: '#007cf5',
				},
			},
		},
	},
	plugins: [],
	prefix: 'wmx-',
};
