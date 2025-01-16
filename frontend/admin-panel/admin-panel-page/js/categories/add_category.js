$(document).ready(()=>{
    const $categoryForm = $('.addCategoryForm')
    const $nameInput = $('#categoryName')
    const $modal = $('addCategoryModal')

    const addCategory = async (name) => {

        const data = new FormData()
        data.append('name', name)

        try {
            const response = await axios.post('http://localhost:3000/e-commerce-inventory-management/backend/category/add.php', data)

            console.log(response.data)

            if(response.data && response.data.status === 'success'){
                console.log('add successful:', response.data);

                $modal.modal('hide');
                location.reload();

            }else{
                console.log(response.data.message || 'adding category failed. Please try again.');
            }
        } catch (error) {
            console.log(error)
        }
    }

    $categoryForm.on('submit', (event)=>{
        event.preventDefault(); 
        const name = $nameInput.val()
        console.log('Category Name:', name);

        addCategory(name)
    })
})