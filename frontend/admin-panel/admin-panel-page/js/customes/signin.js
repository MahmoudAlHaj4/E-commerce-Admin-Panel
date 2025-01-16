$(document).ready(function() {
    const $loginForm = $('.user'); 
    const $loginInput = $('#InputEmail');
    const $loginPasswordInput = $('#InputPassword');
    const $loginValidationDisplay = $('#validationDisplaySignIn'); 

    const clearLoginForm = () => {
        $loginInput.val('');
        $loginPasswordInput.val('');
    };

    const signIn = async (login, password) => {
        const data = new FormData();
        data.append('login', login);
        data.append('password', password);

        try {
            const response = await axios.post('http://localhost:3000/e-commerce-inventory-management/backend/users/signin.php', data);
            
            console.log('the data is : ', response.data);
            if (response.data && response.data.status === 'success' && response.data.token) {
                console.log('Token:', response.data.token);
                localStorage.setItem('token', response.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.user));

                if(response.data.user.role ==='super-admin'){
                    window.location.href = "./index.html"
                }else{
                    console.log('u are a customer')
                }
                
            } else {
                $loginValidationDisplay.html(response.data.message || 'Login failed. Please try again.');
            }
        } catch (error) {
            console.error('Error during signin:', error);
            $loginValidationDisplay.html(error.message);
        }
    };

    $loginForm.on('submit', async (event) =>{
        event.preventDefault();
        $loginValidationDisplay.html('');
        const login = $loginInput.val();
        const password = $loginPasswordInput.val();
        signIn(login, password);
    });
});
