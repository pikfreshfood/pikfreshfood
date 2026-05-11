# Caller React

Standalone React caller test app backed by the local Node caller server.

## Run

1. Start the Node backend in `../caller-server` with `npm run dev`
2. Start this app with `npm run dev`
3. Open the Vite URL and register/login directly inside the React UI
4. Use two accounts to test direct calls

The Vite dev server proxies `/api/*` requests to the local Node caller server on port `3001`.
