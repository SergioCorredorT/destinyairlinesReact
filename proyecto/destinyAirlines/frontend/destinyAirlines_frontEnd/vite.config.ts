import { defineConfig } from 'vite'
import react from "@vitejs/plugin-react";
import million from 'million/compiler';

export default defineConfig({
    assetsInclude: ['**/*.PNG'],
    plugins: [ react(), million.vite({ auto: true })], //Aquí da error en la visualización de código en vscode porque typescript es incompatible con million. Aunque al ejecutarlo vite lo hace funcionar
})
