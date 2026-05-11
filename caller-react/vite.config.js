import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

const callerServerBaseUrl = process.env.VITE_CALLER_SERVER_URL || 'http://localhost:3001';

export default defineConfig({
    plugins: [react()],
    server: {
        host: '0.0.0.0',
        port: 5174,
        allowedHosts: true,
        proxy: {
            '/api': {
                target: callerServerBaseUrl,
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, '/api'),
            },
        },
    },
});
