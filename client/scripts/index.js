
const methods = {
    websocket: null,
    messages: [],
    connect: () => {
        methods.websocket = new WebSocket("ws://localhost:8080/");

        methods.websocket.addEventListener("open", console.log);
        // methods.websocket.addEventListener("message", console.log);

        methods.websocket.addEventListener("message", (event) => {
            
            let message = JSON.parse(event.data);

            console.log(message);

            if(message["type"]== "chat")
                methods.message.receive(message);
        });
    },
    alert: () => {
        alert("chamou o método")
    },
    message: {
        send: () => {
            let message = { type: "chat", text: document.querySelector("input[name=message]").value };
            if(!!message.text) {
                methods.websocket.send(message.text);
                document.querySelector(".chat-messages").innerHTML += `<div class="chat-messages-me"><p>${message.text}</p></div>`;
            }

            document.querySelector("input[name=message]").value = "";
        },
        receive: (message) => {
            document.querySelector(".chat-messages").innerHTML += `<div class="chat-messages-they"><p>${message.text}</p></div>`;
        }
    }, 
    init: () => {
        document.querySelector("input[name=message]").addEventListener("keypress", (e) => {
            if(e.code.toUpperCase() == "ENTER")
                methods.message.send();
        });

        methods.connect();
    }
}

methods.init();