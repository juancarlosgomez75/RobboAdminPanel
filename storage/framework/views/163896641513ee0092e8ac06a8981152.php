<div>
    <script src="https://cdn.jsdelivr.net/npm/@stomp/stompjs@7.0.0/bundles/stomp.umd.min.js"></script>
    <div class="container mt-4" x-data="{
        messages: [],
        stompClient: null,
        initialized: false,
        connectionInitialized: false,
        reconnectInterval: null,
        init() {
            if (!this.connectionInitialized) {
                console.log('Inicializando conexión STOMP');
                this.connect();
                this.connectionInitialized = true;
            }
        },
        connect() {
            // Prevenir reconexión redundante
            if (this.stompClient && this.stompClient.connected) {
                console.log('STOMP ya está conectado');
                return;
            }

            console.log('Estableciendo nueva conexión STOMP');

            this.stompClient = new StompJs.Client({
                brokerURL: 'ws://clientcontrol.robbocock.com:5449/ws', // <-- AJUSTA ESTE ENDPOINT según tu configuración
                connectHeaders: {
                    login: 'guest', // <-- Ajusta si necesitas login
                    passcode: 'guest'
                },
                debug: function (str) {
                    console.log(str);
                },
                reconnectDelay: 5000, // Reconexión automática
                onConnect: (frame) => {
                    console.log('Conexión STOMP abierta');

                    $wire.set('WSConnection', true);
                    if (this.reconnectInterval) {
                        clearInterval(this.reconnectInterval);
                        this.reconnectInterval = null;
                    }

                    // Suscribirse al tópico (ajusta el destino si es diferente)
                    this.stompClient.subscribe('/topic/topicMaquinas', (message) => {
                        console.log('Mensaje recibido:', message.body);
                        $wire.receiveMessage(message.body);
                    });
                },
                onStompError: (frame) => {
                    console.error('STOMP error:', frame.headers['message']);
                },
                onWebSocketClose: () => {
                    $wire.set('WSConnection', false);
                    console.log('Conexión STOMP cerrada');
                    this.reconnect();
                }
            });

            this.stompClient.activate();

            if (!this.initialized) {
                $wire.on('sendmsg', (mensaje) => {
                    const payload = mensaje[0];
                    const jsonString = JSON.stringify(payload);
                    this.sendMessage(jsonString);
                });
                this.initialized = true;
            }
        },
        reconnect() {
            if (this.reconnectInterval) return;
            console.log('Intentando reconectar en 5s...');
            this.reconnectInterval = setInterval(() => {
                this.connect();
            }, 5000);
        },
        sendMessage(message) {
            if (this.stompClient && this.stompClient.connected) {
                const finalMessage = typeof message === 'string' ? message : JSON.stringify(message);
                this.stompClient.publish({ destination: '/topic/topicRobbo', body: finalMessage });
            } else {
                console.error('El cliente STOMP no está conectado.');
            }
        }
    }" x-init="init()">
        <div class="w-100 mb-3">
            <div class="row ps-4 pe-4">
                <div class="col-12 p-3 mb-1">
                    <div class="row text-center">
                        <div class="col-3">
                            <span>Dólares quincena</span><br>
                            <span style="font-weight: bold; color: gray; font-size:1.2rem">$5.25</span>
                        </div>
                        <div class="col-3">
                            <span>Dólares ayer</span><br>
                            <span style="font-weight: bold; color: gray; font-size:1.2rem">$5.25</span>
                        </div>
                        <div class="col-3">
                            <span>Dólares hoy</span><br>
                            <span style="font-weight: bold; color: gray; font-size:1.2rem">$5.25</span>
                        </div>
                        <div class="col-3">
                            <span>Máquinas conectadas</span><br>
                            <span style="font-weight: bold; color: gray; font-size:1.2rem">10</span>
                        </div>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php for($i=0;$i<9;$i+=1): ?>
                <div class="col-12 shadow-sm p-3 mb-1 bg-body-tertiary rounded">
                    <div class="row">
                        <div class="col-12 d-flex align-items-center flex-wrap">
                            <a style="color:#ce3737; font-weight: bold; text-decoration: none;" href="#">XCELSIOR ESTUDIO (RF)</a>
                            <i class="fa-solid fa-arrow-right me-2 ms-2 text-secondary"></i>
                            <a style="font-weight: bold; text-decoration: none; color:black;" href="#">LABTEST2</a>
                            <i class="fa-solid fa-arrow-right me-2 ms-2 text-secondary"></i>
                            <a style="font-weight: bold; text-decoration: none; color:#818181" href="#">200082</a>
                            <i class="fa-solid fa-arrow-right me-2 ms-2 text-secondary"></i>
                            <span>V17.1.1</span>
                        
                            <span class="badge text-bg-secondary ms-5" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom">Autoclean</span>

                        
                            <i class="fa-solid fa-cloud ms-5 text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Máquina conectada"></i>
                            <i class="fa-solid fa-droplet ms-4 text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Cum lleno"></i>
                            <i class="fa-solid fa-sliders ms-4 text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Rangos activos"></i>
                        
                            <a class="ms-5"></a>
                            <a href="#" class="bg-success me-1" style="padding: 5px; text-decoration: none; font-weight: bold; color:white; font-size: 0.8rem;" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Chaturbate On">CH</a>
                            <a href="#" class="bg-secondary me-1" style="padding: 5px; text-decoration: none; font-weight: bold; color:white; font-size: 0.8rem;" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Stripchat off">SC</a>
                        
                            <span class="ms-auto">
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($i); ?>" aria-expanded="false" aria-controls="collapse<?php echo e($i); ?>">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                
                            </span>
                        </div>
                        <div class="col-12 collapse mt-3" id="collapse<?php echo e($i); ?>">
                            <div class="row">
                                <div class="col-12 col-xxl-8 mb-2">
                                    <div class="input-group">
                                        <input type="checkbox" class="btn-check" id="btn-check-4" autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="btn-check-4">Voz</label>
                                        
                                    
                                        <input type="checkbox" class="btn-check" id="btn-check-5" checked autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="btn-check-5">PopUp</label>
                                    
                                        <!-- Input de texto -->
                                        <input type="text" class="form-control" placeholder="Escribe algo para enviar..." aria-label="Texto">
                                    
                                        <!-- Botón de enviar -->
                                        <button class="btn btn-danger" type="button">Enviar</button>

                                        <div class="hidden"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-xxl-4 text-center">
                                    <div class="btn-group ps-4" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-outline-secondary">Test move</button>
                                        <button type="button" class="btn btn-outline-secondary">Test cum</button>
                                        <button type="button" class="btn btn-outline-secondary">Reset</button>
                                        <button type="button" class="btn btn-outline-danger">Cerrar Driver</button>
                                    </div>
                                </div>
                            </div>

                        </div>               
                    </div>
                </div>
                <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <script>
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        </script>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/admin/dashboard.blade.php ENDPATH**/ ?>