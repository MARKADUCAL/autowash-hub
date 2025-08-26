import { createRequestHandler } from "@angular/ssr/node";
import express from "express";
import { dirname, resolve } from "node:path";
import { fileURLToPath } from "node:url";

const __dirname = dirname(fileURLToPath(import.meta.url));
const distFolder = resolve(__dirname, "../dist/autowash-hub");
const serverDistFolder = resolve(distFolder, "server");
const browserDistFolder = resolve(distFolder, "browser");

const app = express();

// Serve static files from /browser
app.use(
  express.static(browserDistFolder, {
    maxAge: "1y",
    index: false,
    redirect: false,
  })
);

// Create the Angular SSR request handler
const requestHandler = createRequestHandler({
  distFolder,
  serverDistFolder,
  browserDistFolder,
});

// Handle all requests with Angular SSR
app.use("*", requestHandler);

export default app;
