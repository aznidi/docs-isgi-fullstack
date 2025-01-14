/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./index.html",
      "./src/**/*.{js,jsx,ts,tsx}",
      "./node_modules/@shadcn/ui/components/**/*.{js,ts,jsx,tsx}",
    ],
    theme: {
      extend: {
        fontFamily: {
          poppins: ['"Poppins"', "sans-serif"],
          montserrat: ['"Montserrat"', "sans-serif"],
        },
        colors: {
          primary: {
            DEFAULT: "#2563EB", // Bleu principal
            light: "#3B82F6",   // Bleu clair
            dark: "#1E40AF",    // Bleu foncé
          },
          secondary: {
            DEFAULT: "#1E40AF", // Bleu foncé pour secondary (lié à primary)
            light: "#2563EB",   // Bleu principal pour hover sur secondary
            dark: "#1B3A76",    // Bleu encore plus foncé
          },
          neutral: {
            DEFAULT: "#6B7280", // Gris pour textes secondaires
            light: "#D1D5DB",   // Gris clair pour fonds et bordures
            dark: "#374151",    // Gris foncé pour textes importants
          },
          background: {
            DEFAULT: "#F9FAFB", // Fond principal blanc cassé
            dark: "#1F2937",    // Fond sombre
          },
          danger: {
            DEFAULT: "#EF4444", // Rouge pour alertes
            dark: "#B91C1C",    // Rouge foncé
          },
          warning: {
            DEFAULT: "#F59E0B", // Jaune pour alertes
          },
          success: {
            DEFAULT: "#10B981", // Vert pour succès (inchangé si utilisé ailleurs)
          },
        },
        keyframes: {
            "accordion-down": {
              from: { height: "0" },
              to: { height: "var(--radix-accordion-content-height)" },
            },
            "accordion-up": {
              from: { height: "var(--radix-accordion-content-height)" },
              to: { height: "0" },
            },
          },
          animation: {
            "accordion-down": "accordion-down 0.2s ease-out",
            "accordion-up": "accordion-up 0.2s ease-out",
          },
      },
    },
    plugins: [],
  };
