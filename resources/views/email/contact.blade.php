<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Message de {{$name}} {{$surname}}</title>
        <style>
            body {
                font-family: sans-serif;
            }
            #msg {
                width: 100%;
                min-height: 200px;
                font-style: italic;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-success">
            <div class="navbar-brand">
                <img src="http://localhost:3000/static/media/vmk_v5_1.bb6934da.svg" />
            </div>
        </nav>
        <div class="container">
            <h1>Cher administrateur,</h1>
            <hr>
            <strong>{{$name}} {{$surname}}</strong> a une question :
            <br><br>
            <p>
            <textarea id="msg" class="form-control" disabled>
                {{$msg}}
            </textarea>
            </p>
            <br>
            Son adresse mail est : <u>{{$email}}</u>
            <br><br>
            Cordialement,
            <br><br>
            Votre fameux site web.
            <br><br>
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAAAdCAYAAABc4wsYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAT8SURBVHhe7ZzbTRxLFEUHZ0EAZEESEAESKUAIZAAffBMBASASIAEIgATg3/Yyfawzm139GBpK2GdJWwxd1fU4r6oxunfv5282RVF048fwsyiKTlQSFkVnKgmLojOVhEXRmUrCoujM6kn4+vq6OT8/3+zt7f0Rn3m2Jk9PT5vj4+O/c1xdXQ0tRfEN4U8Ua3J4eMifPLbEs7V4fn5+Nz66uLgYehSfxePj4zu744/vysHBwdZebm5uhpavZdUkdE4K0bYGGMqNj4rPRW1PEH9XXDHvVVD+i++EXFfj6lrszvX19fDpjZOTk+HT17KGP+/u7oZPb/wuKJv9/f3hty9mSMbV2PU6Gqfo1LVy6XU09z87OxueFkt5eXnZsje6v78fWr+Otfx5dHS0tZeeX2dWT0KchXGyoXg2Bu35fv7w8DC0eEjYbMTLy8uh5T2np6d/+93e3g5Pi6Vgu7BjKMB/UXz5OeXvj7CGP1lfjBHqUVCC7l+ksgNDa31/JDnzuJ8ZHP86ubAikgHUf59p47X8ScLlcVBPuiehGnaNL/sYWa8bc67ERZtsSxT/kpgTcK3iqaztz1ZB6YVNwrxApNdDTZwxTTmGsfWdsX+lcnNnuNtru0r3Q0UlqNTRIZzUuvrwrvbXvoydg5U9BBpg7lo0tb6lyicIa81rc7aP7+tZ9NNgRozVOqGYC1vqnwY+qqmvL4q+j21Z85zYGRP7wiaL1zP83EIHz4O6pBnTGGw8B0Bo7Euy9tcqNuXgnADgriYtRfXPEFjaLwKZ4HX7Yw3snbVrm87RGmNXhb1a87sE0uCMYMvPslyRc3OtIfXnFK2Cgt31+Uc0dfhkFiUhi41nOEIdpsGCo1rw7lhwOXhH+7kTygWNI+8nK9DAccVBg5E9wZhTXeKG1KZ6+uW95DliXiW/i5i7ldjM5RjzkzudFXd7yYXKPc/M9eccdC0xllsjimRyxToXNMbJbe5G08JGuxqdJNSk0Ux3Ad1aSB6LIFZHIB0fXPBq0IIapFUM1LkoEk2NzpguQHIfhDN3vTbqOp1dcuXXfeoJ5AJnrDjoKQytQoXwhxaqCMxMbkc52bWI5f0Fc/2pRGIRa+E7je3wt7M18wb6Hor41jhqFbMWNgk1iHBuNpY6G5xzl6DvujnU4W6zS4qBSxaXaC2c40I4Tdeb5Sqvzo0NWn1cm9pMAzzLzT+38CGeQ+t5ptWHAqptmoRL/JlR3+CLqbE00XJbfo5ygn6UWUmYg8kZGTTg+F3B6LkfnxkP5XcxhiP3Qa5yLykG7iR0gdjCvY947hyOsC1t+q6zlxsjEk3t7YJCTxCEbdmjJmErqHQeFDEwViQyuo5INOcrfX/X4q5rw+4aZyj6agLqaavt7MndwnZhVhKGCJwWrr/KGWGunDOcWmt3wuEuUOeI99QxKKqnWy/PAveuyhWolrR46EmACKwInDl2mjotXbuKedxanCK5M0v9GeicFJM568Wv7qR1BWeupq7Ps5PQVerM2AbZGO16Es4V78KYQwhqjDcnaLOhWRPJscTZ7EFPKd7PQcD40YYTtMJPrTMXPAKAMbRgMGfYVclFIGyTmQpI2vMeGEMTfSowc9Kzf/akxYffnX2Cpf7MxB6Zg/FbxSDW4IpAJvawJFbo7/yTqf/bWlF0pv7L+qLoTCVhUXSmkrAoOlNJWBSdqSQsis5UEhZFVzabX9FHuzWlvGw7AAAAAElFTkSuQmCC" />
        </div>
    </body>
</html>