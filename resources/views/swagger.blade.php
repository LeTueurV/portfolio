<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Portfolio API - Swagger Documentation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4/swagger-ui.css">
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4/swagger-ui-bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            // Charger la spec depuis le serveur
            fetch('/api/openapi.yaml')
                .then(response => response.text())
                .then(yaml => {
                    // Convertir YAML en Object (très simple parser)
                    const spec = jsyaml.load(yaml);
                    
                    window.ui = SwaggerUIBundle({
                        spec: spec,
                        url: '/api/openapi.yaml',
                        dom_id: '#swagger-ui',
                        deepLinking: true,
                        presets: [
                            SwaggerUIBundle.presets.apis,
                            SwaggerUIStandalonePreset
                        ],
                        plugins: [
                            SwaggerUIBundle.plugins.DownloadUrl
                        ],
                        layout: "StandaloneLayout"
                    });
                })
                .catch(error => {
                    document.getElementById('swagger-ui').innerHTML = `
                        <div style="padding: 20px; color: red;">
                            <h2>Erreur</h2>
                            <p>${error.message}</p>
                            <p>Essayez d'accéder à <a href="/api/openapi.yaml">/api/openapi.yaml</a></p>
                        </div>
                    `;
                });
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/js-yaml@4/dist/js-yaml.min.js"></script>
</body>
</html>
