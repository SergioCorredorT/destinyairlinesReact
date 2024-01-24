[Seleccionadas]
    typescript
    [pasivos]
        Million.js (optimiza velocidad sin hacer nada, porque usa un DOM virtual mejor), instalas el paquete million y modificas el vite.config.js
        Vite
        normalize.css

    [activos]
        CSS Modules
            Necesario crear para que vscode no vea como error por culpa de typescript, aunque ya funcione el proyecto:
                declarations.d.tsx
                tsconfig.json

        fetch o axios (viciarse el fetch primero)
        Zustand (por preferir simplicidad a la funcionalidad del useContext)
        zod+react hook form (formik está menos preparado para typescript)
        react router dom
        Jest para test ¿ + testing library?

[hosting]
    Netlify                 para frontend
    heroku o 000webhost     para php de backend
    000webhost              para sql MariaDB de bbdd

[Descartadas]
    useSignal (useState) Al ser de Preact parece no ser completamente compatible con React, no me funcionó para mostrar el panel sign in al clicar en su botón sign in
    Vitest (por simplicidad)