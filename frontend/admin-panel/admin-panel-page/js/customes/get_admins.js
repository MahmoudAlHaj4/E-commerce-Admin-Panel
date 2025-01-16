$(document).ready(function(){
    const $dataTable = $('#dataTable');
    const $tableBody = $dataTable.find('tbody'); 
    const $errorDisplay = $('#errorDisplay');


    const loadAdmind = async () => {

        try{
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/users/get_admins.php')
            console.log('Response:', response)
            let admins = response.data.admins
            console.log(admins)

            
            let tableRows = '';
            $.each(admins, (index, admin) => {
                tableRows += `<tr>
                                <td>${admin.id}</td>
                                <td>${admin.username}</td>
                                <td>${admin.email}</td>
                                <td>${admin.role}</td>
                                <td>${admin.created_at}</td>
                              </tr>`;
            });
            $tableBody.html(tableRows); 
            
        }catch (error){
            console.error('Error loading admins data:', error);
            $errorDisplay.html('Failed to load admin data. Please try again.'); 
        }

    }
    loadAdmind()
})