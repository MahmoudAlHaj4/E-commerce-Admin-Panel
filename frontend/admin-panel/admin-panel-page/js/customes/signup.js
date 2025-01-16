$(document).ready(function() {
    const $registerForm = $('.user'); 
    const $usernameInput = $('#username'); 
    const $emailInput = $('#email');
    const $passwordInput = $('#password'); 
    const $repeatPasswordInput = $('#repeatPassword'); 
    const $validationDisplay = $('#validationDisplaySignUp'); 

    const clearRegisterForm = () => {
        $usernameInput.val('');
        $emailInput.val('');
        $passwordInput.val('');
        $repeatPasswordInput.val('');
    };

    const registerUser = async (username, email, password) => {
        const data = new FormData();
        data.append('username', username);
        data.append('email', email);
        data.append('password', password);

        try {

            const response = await axios.post('http://localhost:3000/e-commerce-inventory-management/backend/users/signup.php', data);
            
            console.log('API response:', response.data); 
            if (response.data && response.data.status === 'success') {
                console.log('Registration successful:', response.data);
                $validationDisplay.html('Registration successful!');
                window.location.href = './login.html' 
                clearRegisterForm();
            } else {
                $validationDisplay.html(response.data.message || 'Registration failed. Please try again.');
            }
        } catch (error) {
            console.error('Error during registration:', error);
            $validationDisplay.html(error.message);
        }
    };

    $registerForm.on('submit', (event) => {
        event.preventDefault();
        $validationDisplay.html(''); 
        const username = $usernameInput.val();
        const email = $emailInput.val();
        const password = $passwordInput.val();
        const repeatPassword = $repeatPasswordInput.val();

        if (password !== repeatPassword) {
            $validationDisplay.html('Passwords do not match.');
            return;
        }

        registerUser(username, email, password);
    });
});
