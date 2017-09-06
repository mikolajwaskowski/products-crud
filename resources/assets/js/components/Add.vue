<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <h1 class="mt-3">Dodaj produkt:</h1>
                <form class="my-5">
                    <div class="form-group">
                        <input v-model="product.name" type="text" class="form-control" placeholder="Nazwa produktu">
                    </div>
                    <div class="form-group">
                        <textarea v-model="product.description" class="form-control" rows="5" placeholder="Opis produktu"></textarea>
                    </div>
                    <h4 class="mt-4 mb-3">Ceny produktu:</h4>
                        <table class="table table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>Aktywna</th>
                                    <th>Wartość [zł]</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(price, index) in product.prices">
                                    <td class="text-center">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" :value="index" v-model="product.activePrice">
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="Wpisz cenę" v-model.number="price.amount">
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <button @click.prevent="addPrice()" class="btn btn-primary btn-sm float-right">Dodaj kolejną cenę</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <button @click.prevent="storeProduct()" class="btn btn-success btn-lg mt-3">Dodaj produkt</button>
                </form>
            </div>
             <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <button @click="back()" class="btn btn-secondary">Wróć</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['back'],
        data() {
            return {
                product: {
                    name: '',
                    description: '',
                    prices: [ { amount: 100 } ],
                    activePrice: 0
                },
                errors: {},
                errorsLength: 0
            };
        },
        methods: {
            storeProduct() {
                let passedValidation = true;

                if(passedValidation) {
                    axios.post('api/products', this.product )
                        .then((res) => {
                            console.log(res);
                            this.back();
                        })
                        .catch((err) => console.error(err));
                }
            },
            addPrice() {
                this.product.prices.push( { amount: null } );
            }
        }
    }
</script>
