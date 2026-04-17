<!DOCTYPE html>
<html>
    <head>
        <title>FCM</title>
        <!-- firebase integration started -->

<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script>
<!-- Firebase App is always required and must be first -->
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-app.js"></script>

<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-messaging.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-functions.js"></script>

<!-- firebase integration end -->

<!-- Comment out (or don't include) services that you don't want to use -->
<!-- <script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-storage.js"></script> -->

<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.8.0/firebase-analytics.js"></script>

    </head>
    <body>
        Firebase Notification
    </body>

    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyBppfct1EwlyUSAT9QKbiuo4e6HiMvV4Fs",
            authDomain: "royo-apps-1624361718359.firebaseapp.com",
            // databaseURL: "https://royo-apps-1624361718359-default-rtdb.firebaseio.com",
            projectId: "royo-apps-1624361718359",
            storageBucket: "royo-apps-1624361718359.appspot.com",
            messagingSenderId: "1030919748357",
            appId: "1:1030919748357:web:9c29df0aca70b4f508156c",
            measurementId: "G-EFBPR3ZDKE"
        };

        // var firebaseConfig = {
        //     apiKey: "AIzaSyBtE2uCaikxgUDbn5SqmzW2fGcGOpUlkqc",
        //     authDomain: "royo-order-version2.firebaseapp.com",
        //     projectId: "royo-order-version2",
        //     storageBucket: "royo-order-version2.appspot.com",
        //     messagingSenderId: "1073948422654",
        //     appId: "1:1073948422654:web:4dd137a854484fa3c410af",
        //     measurementId: "G-59QSSL4RQ1"
        // };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        messaging
        .requestPermission()
        .then(function () {
            console.log("Notification permission granted.");
            return messaging.getToken()
        })
        .then(function(token) {
        console.log(token);
        })
        .catch(function (err) {
            console.log("Unable to get permission to notify.", err);
        });
        messaging.onMessage(function(payload) {
            console.log(payload);
            var notify;
            notify = new Notification(payload.notification.title,{
                body: payload.notification.body,
                icon: payload.notification.icon,
                tag: "Dummy"
            });
            console.log(payload.notification);
        });
        var database = firebase.database().ref().child("/users/");
        database.on('value', function(snapshot) {
            renderUI(snapshot.val());
        });
        database.on('child_added', function(data) {
            console.log("Comming");
            if(Notification.permission!=='default'){
                var notify;
                
                notify= new Notification('CodeWife - '+data.val().username,{
                    'body': data.val().message,
                    'icon': 'bell.png',
                    'tag': data.getKey()
                });
                notify.onclick = function(){
                    // alert(this.tag);
                }
            }else{
                // alert('Please allow the notification first');
            }
        });
        self.addEventListener('notificationclick', function(event) {       
            event.notification.close();
        });

    </script>
</html>