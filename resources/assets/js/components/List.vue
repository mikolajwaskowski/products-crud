<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3"> 
                <button @click="add" class="btn btn-success btn-block btn-lg mb-4"><i class="fa fa-plus"></i>  Dodaj</button>
            </div>
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div v-if="list.length === 0" class="m-5 text-center" v-html="status"></div>
                <ul v-else class="list-group">
                    <li v-for="(product, index) in list" class="list-group-item flex-column align-items-start p-4">
                        <div class="d-flex w-100 justify-content-between">
                            <h3 class="mb-1">{{ product.name }}</h3>
                            <h4 v-for="price in product.prices" v-if="price.active">
                                <span v-if="price.active" class="badge badge-primary badge-pill">
                                    {{ price.amount }} zł
                                </span>
                            </h4>
                        </div>                        
                        <p>{{ product.description }}</p>
                        <div class="align-self-end ml-auto">
                            <button @click="editProduct(product.id)" class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i>  Edytuj</button>
                            <button @click="deleteProduct(product.id, $event)" class="btn btn-outline-danger btn-sm ml-2"><i class="fa fa-trash"></i>  Usuń</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['add', 'edit'],
        data() {
            return {
                status: 'Wczytywanie produków...',
                list: []
            };
        },
        created() {
            this.fetchProducts();
        },
        methods: {
            fetchProducts() {
                // empty old array
                this.list = [];

                // get data
                axios.get('api/products')
                    .then((res) => {
                        if(res.data.error) {
                            this.status = '<i class="fa fa-5x fa-inbox text-muted"></i><br /> ' + res.data.error.message + ' Dodaj pierwszy produkt za pomocą przycisku "Dodaj" powyżej.';
                        } else {
                            this.list = res.data;
                        }
                    })
                    .catch((err) => {
                        this.status = '<i class="fa fa-5x fa-frown-o text-muted"></i><br /> Wystąpił błąd podczas wczytywania.'
                    });
            },
 
            createProduct() {
                axios.put('api/products', this.product)
                    .then((res) => {
                        this.fetchProducts();
                    })
                    .catch((err) => console.error(err));
            },
 
            deleteProduct(id, event) {
                // show status
                event.target.disabled = true;
                event.target.innerText = 'Usuwanie...';

                // delete item
                axios.delete('api/products/' + id)
                    .then((res) => {
                        this.fetchProducts();
                    })
                    .catch((err) => console.error(err));
            },
            editProduct(id) {
                this.edit(id)
            }
        }
    }
</script>
