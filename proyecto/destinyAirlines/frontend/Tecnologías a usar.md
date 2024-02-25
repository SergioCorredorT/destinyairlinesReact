[Seleccionadas]
    ✅ typescript
    [pasivos]
        ✅ Million.js (optimiza velocidad sin hacer nada, porque usa un DOM virtual mejor), instalas el paquete million y modificas el vite.config.js
        ✅ Vite
        ✅ resetCSS (https://www.youtube.com/watch?v=Foieq2jTajE&list=PLJpymL0goBgHH9APAeYt5ytE9eT4-lFvE&index=1)

    [activos]
        ✅ CSS Modules
            Necesario crear para que vscode no vea como error por culpa de typescript, aunque ya funcione el proyecto:
                declarations.d.tsx
                tsconfig.json
        ❓ Signal , useSignal (sustitución de useState) (signal para crear la señal, useSignal para forzar re-renderizado )
        ✅ fetch
        ✅ Zustand (por preferir simplicidad a la funcionalidad del useContext)
        ✅ zod+react hook form (formik está menos preparado para typescript)
        🔄 react select (select personalizados)
        🔄 react router dom
        🔄 react lazy load image component + tipos
        🔄 Jest para test ¿ + testing library?

[hosting]
    🔄Todo
            ✅000webhost (aunque es anual)
    🔄 frontend
            Netlify
            000webhost
            vercel
    🔄 backend + bbdd
            ❓railway
            ❓infinityfree
            ❓back4app
            ❌000webhost (cors por peticiones http externas)
            ❌heroku (tarj. banco)
            ❌googleCloud (tarj. banco)
            ❌aws (tarj. banco)
    🔄 bbdd
            

[Descartadas]
    ❌ axios (únicamente por viciarse el fetch primero)
    ❌ normalize.css (mejor mi resetCSS)
    ❌ Vitest (por simplicidad)