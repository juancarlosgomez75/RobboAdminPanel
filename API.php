<?php 
    function obtenerListadoMaquinas($id_search){
        $url = "https://robbocock.online:8443/WSIntegration-1.0/resources/restapi/transaction"; // URL de la API
        $token = "token"; // Reemplaza con tu token de autorización

        // Datos a enviar (pueden ser JSON o un array asociativo)
        $data = [
            "Branch" => "Server",
            "Service" => "Machines",
            "Action" => "View",
            "Data" => [
                "UserId" => $id_search
            ]
        ];

        // Convierte el array a JSON si la API lo requiere
        $jsonData = json_encode($data);
        
        // Inicializa cURL
        $ch = curl_init($url);
        
        // Configura las opciones
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas con HTTPS
        curl_setopt($ch, CURLOPT_POST, true); // Método POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Datos a enviar
        
        // Encabezados (incluye Authorization con el token)
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token", // Autenticación con token
            "Content-Type: application/json",
            "Content-Length: " . strlen($jsonData)
        ]);
        
        // Ejecuta la solicitud
        $response = curl_exec($ch);
        
        // Manejo de errores
        if (curl_errno($ch)) {
            echo 'Error en cURL: ' . curl_error($ch);
        }
        
        // Cierra cURL
        curl_close($ch);

        //Obtengo la data
        $data=json_decode($response, true);

        //Retorno las maquinas
        return $data["Data"]["Machines"];
    }

    function obtenerListadoEstudios(){

        $url = "https://robbocock.online:8443/WSIntegration-1.0/resources/restapi/transaction"; // URL de la API
        $token = "token"; // Reemplaza con tu token de autorización

        // Datos a enviar (pueden ser JSON o un array asociativo)
        $data = [
            "Branch" => "Server",
            "Service" => "PlatformUser",
            "Action" => "UserList",
            "Data" => [
                "UserId" => "1"
            ]
        ];
        
        // Convierte el array a JSON si la API lo requiere
        $jsonData = json_encode($data);
        
        // Inicializa cURL
        $ch = curl_init($url);
        
        // Configura las opciones
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas con HTTPS
        curl_setopt($ch, CURLOPT_POST, true); // Método POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Datos a enviar
        
        // Encabezados (incluye Authorization con el token)
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token", // Autenticación con token
            "Content-Type: application/json",
            "Content-Length: " . strlen($jsonData)
        ]);
        
        // Ejecuta la solicitud
        $response = curl_exec($ch);
        
        // Manejo de errores
        if (curl_errno($ch)) {
            echo 'Error en cURL: ' . curl_error($ch);
        }
        
        // Cierra cURL
        curl_close($ch);

        return json_decode($response, true);
    };

    function consultarEstudioValido($id_search){
        //Consulto los estudios
        $dataResponse=obtenerListadoEstudios();

        //Filtro
        if (isset($dataResponse['Status']) && isset($dataResponse['ListUserData'])){
            //Recorro
            foreach ($dataResponse['ListUserData'] as $user){
                if($user["Id"]==$id_search){

                    if($user["Activo"]==true){
                        return $user;
                    }
                }
            }
        }
        return false;
    }
    
?>