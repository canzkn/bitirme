let app = require('express')();
let server = require('http').createServer(app);
let io = require('socket.io')(server);
const {
    userJoin,
    getCurrentUser,
    userLeave
  } = require('./utils/users');

var port = process.env.PORT || 3001;

io.on('connection', (socket) => {
    socket.on('joinGroup', ({ UserID, GroupID }) => {
        console.log('UserID: ' + UserID + ' GroupID: ' + GroupID + ' Joined.');
        const user = userJoin(socket.id, UserID, GroupID);
        socket.join(user.GroupID);
    });

    socket.on('send-message', message => {
        console.log(message);
        const user = getCurrentUser(socket.id);
        io.to(user.GroupID).emit('message', message);
    });

    socket.on('disconnect', function(){
        const user = userLeave(socket.id);
    });
});

server.listen(port, function(){
    console.log(`Server running on port ${port}`);
});