document.getElementById('loginButton').addEventListener('click', function () {
    const loginUrl = 'login.html';  // URL of the login page
    const windowFeatures = 'width=600,height=400,top=100,left=100';
    const loginWindow = window.open(loginUrl, '_blank', windowFeatures);

    // Monitor if the login window is closed
    const checkWindowClosed = setInterval(function () {
        if (loginWindow.closed) {
            clearInterval(checkWindowClosed);
            alert('Login window was closed without completing the login process.');
        }
    }, 500);

    window.addEventListener('message', function (event) {
        if (event.origin === window.location.origin) {
            const isLoggedIn = event.data.isLoggedIn;

            if (typeof isLoggedIn === 'boolean') {
                clearInterval(checkWindowClosed); // Clear the interval if we receive a message

                if (isLoggedIn === true) {
                    alert('Login successful!');
                } else if(isLoggedIn === false){
                    alert('Login failed!');
                }
            }
        }
    });
});
