importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "{{$preference->fcm_api_key}}",
    authDomain: "{{$preference->fcm_auth_domain}}",
    projectId: "{{$preference->fcm_project_id}}",
    storageBucket: "{{$preference->fcm_storage_bucket}}",
    messagingSenderId: "{{$preference->fcm_messaging_sender_id}}",
    appId: "{{$preference->fcm_app_id}}",
    measurementId: "{{$preference->fcm_measurement_id}}"
});

const messaging = firebase.messaging();