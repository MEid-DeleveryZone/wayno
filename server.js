const express = require('express');

const app = express();

const server = require('http').createServer(app);

const io = require('socket.io')(server, {
    cors: { origin: "*"}
});

const sourceFile = require('./node_variables');

io.on('connection', (socket) => {
    socket.join(socket.handshake.query.user_id)
    console.log(socket.handshake.query.user_id+' user connected');

    socket.on('sendChatToServer', (message) => {
        console.log(message);
        io.sockets.emit('sendChatToClient', message);
    });

    socket.on('createOrder', (orderData) => {
        console.log("order created");
        console.log(orderData);
        for (const [key, value] of Object.entries(orderData.admins)) {
            io.sockets.emit('createOrderByCustomer_'+socket.handshake.query.subdomain+"_"+value, orderData);
        }
        // (orderData.admins).forEach(element => {
        //     io.sockets.emit('createOrderByCustomer_'+socket.handshake.query.subdomain+"_"+element, orderData);
        // });
    });
    
    socket.on('disconnect', () => {
        console.log(socket.handshake.query.user_id+' user disconnected');
    });
})
server.listen(sourceFile.socket_port, () => {
    console.log('Server is running');
})