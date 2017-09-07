<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <h2 class="mt-3">Edytuj produkt:</h2>
                <form class="my-4">
                    <div class="form-group" :class="{ 'has-danger': errors.name }">
                        <input v-model="product.name" v-on:keydown="validate" type="text" class="form-control form-control-lg" :class="{ 'form-control-danger': errors.name }" placeholder="Nazwa produktu">
                        <div v-if="errors.name" class="form-control-feedback"><small>{{ errors.name }}</small></div>
                    </div>
                    <div class="form-group" :class="{ 'has-danger': errors.description }">
                        <textarea v-model="product.description" v-on:keydown="validate" class="form-control" rows="5" :class="{ 'form-control-danger': errors.description }" placeholder="Opis produktu"></textarea>
                        <div v-if="errors.description" class="form-control-feedback"><small>{{ errors.description }}</small></div>
                    </div>
                    <h4 class="mt-5 mb-3"><i class="fa fa-tags"></i>&nbsp; Ceny produktu:</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Aktywna</th>
                                    <th>Wartość [zł]</th>
                                    <th>Usuń</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(price, index) in product.prices">
                                    <td class="text-center">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" :value="index" v-model="activeIndex">
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group" :class="{ 'has-danger': errors.price && errors.price.length }">
                                            <input v-model.number="price.amount" v-on:keydown="validate" type="number" class="form-control" placeholder="Wpisz cenę" >
                                        </div>
                                    </td>
                                    <td>
                                        <button v-if="index == (product.prices.length -1) && index !== activeIndex" @click.prevent="deletePrice(index)" class="btn btn-outline-danger"><i class="fa fa-minus-circle"></i></button>
                                        <button v-else class="btn btn-outline-secondary" disabled><i class="fa fa-minus-circle text-dimmed"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <span v-if="errors.price && errors.price.length" class="text-danger mx-3"><small> {{ errors.price }} </small></span>
                                        <button @click.prevent="addPrice" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus-circle"></i> Dodaj cenę</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <div v-if="status" class="alert alert-danger text-center" v-html="status"></div>
                        <div v-if="errors.length" v-for="error in errors" class="alert alert-danger text-center">
                            <div><i class="fa fa-arrow-right"></i> {{ error }}</div>
                        </div>
                        <button @click.prevent="updateProduct" class="btn btn-success btn-lg btn-block mt-3 btn-send"><i class="fa fa-edit"></i> Edytuj produkt</button>
                </form>
            </div>
             <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <button @click="back" class="btn btn-secondary"><i class="fa fa-angle-double-left"></i>  Wróć</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['back', 'productid'],
        data() {
            return {
                product: {
                    name: '',
                    description: '',
                    prices: [ { active: 0, amount: null } ],
                },
                activeIndex: 0,
                errors: {},
                status: '',
                formSubmitted: false
            };
        },
        created() {
            this.getProduct();
        },
        methods: {
            setActivePrice() {
                // TODO
            },
            getProduct() {
                // get data
                axios.get('api/products/' + this.productid)
                    .then((res) => {
                        if(res.data.error) {
                            this.status = '<i class="fa fa-5x fa-exclamation-circle text-danger"></i><br /> ' + res.data.error.message;
                        } else {
                            this.product = res.data;
                            let activePrice;
                            this.product.prices.map( function(price, index) {
                                if ( price.active === 1 ) {
                                    activePrice = index
                                }
                            });
                            this.activeIndex = activePrice;
                        }
                    })
                    .catch((err) => {
                        this.status = '<i class="fa fa-5x fa-frown-o"></i><br /> Wystąpił błąd podczas wczytywania danych.'
                    });
                // setActivePrice();
            },
            validate() {
                if(this.formSubmitted) {
                    this.errors = [];

                    let passed = true;
                    if( this.product.name == '' ){
                        this.errors.name = 'Wpisz nazwę produktu.';
                        passed = false;
                    }
                    if( this.product.description == '' ){
                        this.errors.description = 'Wpisz opis produktu.';
                        passed = false;
                    }

                    // prices validations
                    let priceEmptyError = false;
                    let priceAmountError = false;
                    this.product.prices.map( function( price ) {
                        if(price.amount < 0 || !price.amount ) priceEmptyError = true;
                        if(price.amount > 999999.99 ) priceAmountError = true;
                    });
                    if ( priceEmptyError ) { 
                        this.errors.price = 'Wpisz wartości (dodatnie) dla cen.';
                        passed = false;
                    }
                    if ( priceAmountError ) {
                        this.errors.price = 'Maksymalna wartość ceny to 999 999,99';
                        passed = false;
                    }

                    return passed;
                }
            },
            updateProduct(event) {
                this.formSubmitted = true;
                let passedValidation = this.validate();

                if(passedValidation) {
                    // loading 
                    this.status = ''; // clear errors
                    event.target.disabled = true; // make button 'loading'

                    // set active price
                    let activePrice = this.activeIndex;
                    this.product.prices.map( function(price, index) {
                        if ( index === activePrice ) price.active = 1;
                        else price.active = 0;
                    });

                    // send request
                    axios.put('api/products', this.product )
                        .then((res) => {
                            if(res.data.error) {
                                this.status = '<i class="fa fa-5x fa-frown-o text-danger"></i><br />' + res.data.error.message;
                                event.target.disabled = false;
                            } else {
                                this.back();
                            }
                        })
                        .catch((err) => {
                            console.error(err);
                            this.status = '<i class="fa fa-5x fa-frown-o text-danger"></i><br /> Wystąpił błąd podczas tworzenia produktu...';
                            event.target.disabled = false;
                        });
                }
            },
            deletePrice(index) {
                this.product.prices.pop(index)
            },
            addPrice() {
                this.product.prices.push( { active: 0, amount: null } );
            }
        }
    }
</script>
