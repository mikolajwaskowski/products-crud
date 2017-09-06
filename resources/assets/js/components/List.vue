<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3"> 
                <button @click="add" class="btn btn-success btn-block btn-lg mb-4">Dodaj</button>
            </div>
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div v-if="list.length === 0" class="m-5 text-center">{{ status }}</div>
                <ul v-else class="list-group">
                    <li class="list-group-item flex-column align-items-start p-4" v-for="(product, index) in list">
                        <div class="d-flex w-100 justify-content-between">
                            <h3 class="mb-1">{{ product.name }}</h3>
                            <div v-if="product.active_price < 0">
                                Produkt niedostępny
                            </div>
                            <h4 v-else>
                                <span class="badge badge-primary badge-pill">
                                    {{ product.active_price }} zł
                                </span>
                            </h4>
                        </div>                        
                        <p>{{ product.description }}</p>
                        <div class="align-self-end ml-auto">
                            <button @click="editProduct(product.id)" class="btn btn-outline-warning btn-sm">Edytuj</button>
                            <button @click="deleteProduct(product.id)" class="btn btn-outline-danger btn-sm ml-2">Usuń</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['add'],
        data() {
            return {
                status: 'Wczytywanie produków...',
                list: [],
                product: {
                    id: '',
                    name: '',
                    descruption: '',
                    active_price: null
                }
            };
        },
        created() {
            this.fetchProducts();
        },
        methods: {
            fetchProducts() {
                axios.get('api/products')
                    .then((res) => {
                        if(res.data.error) {
                            this.status = res.data.error.message;
                        } else {
                            this.list = res.data;
                        }
                    })
                    .catch((err) => {
                        this.status = 'Wystąpił błąd podczas wczytywania :('

                    });
            },
 
            createProduct() {
                axios.put('api/products', this.product)
                    .then((res) => {
                        console.log(res);
                        this.fetchProducts();
                    })
                    .catch((err) => console.error(err));
            },
 
            deleteProduct(id) {
                axios.delete('api/products/' + id)
                    .then((res) => {
                        console.log(res);
                        this.fetchProducts();
                    })
                    .catch((err) => console.error(err));
            },
        }
    }
</script>
