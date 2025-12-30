import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        {
            name: 'copy-font-awesome-fonts',
            enforce: 'post',
            apply: 'build', // يتم التشغيل فقط عند npm run build
            async writeBundle() {
                const fs = await import('fs');
                const path = await import('path');

                const sourceDir = path.resolve(
                    __dirname, 
                    'node_modules/@fortawesome/fontawesome-free/webfonts'
                );
                const targetDir = path.resolve(__dirname, 'public/build/assets/webfonts'); // أو public/webfonts
                
                // تأكدي من وجود مجلد الهدف
                if (!fs.existsSync(targetDir)) {
                    fs.mkdirSync(targetDir, { recursive: true });
                }

                fs.readdirSync(sourceDir).forEach(file => {
                    fs.copyFileSync(
                        path.join(sourceDir, file),
                        path.join(targetDir, file)
                    );
                });
            }
        }
    ],
});
