Cоздание кастомной страницы на inertia:
1. app/Http/Middleware/HandleInertiaRequests.php в rootView проверяется путь и возвращается соответствующий blade файл
2. В этом blade файле указывается какой используется js файл: @vite('resources/js/make-article.js')
При другом пути мы просто проверим в HandleInertiaRequests.php и вернём другой blade файл в котором будет ссылка ну другой 
собранный js
3. В public/build/manifest.json мы указываем какие файлы будут собираться и из чего



Для подробностей посмотреть коммит d66d3636046391bb71f56cda37822b93b98e224e  называется set inertia
