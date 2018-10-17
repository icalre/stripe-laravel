<template>
    <form action="/subscriptions" method="POST">
        <input type="hidden" name="stripeToken" v-model="stripeToken">
        <input type="hidden" name="stripeEmail" v-model="stripeEmail">

        <div class="form-group">
            <select name="plan" v-model="plan" class="form-control">
                <option v-for="plan in plans" :value="plan.id">
                    {{plan.name}} &mdash; ${{plan.price / 100}}
                </option>
            </select>
        </div>
        <div class="form-group">
            <input class="form-control" type="text" name="coupon" v-model="coupon" placeholder="Have a coupon code?">
        </div>

        <button type="submit" class="btn btn-primary" @click.prevent="buy()">
            Subscribe
        </button>
        <p class="help is-danger" v-show="status" v-text="status">{{status}}</p>
    </form>
</template>

<script>
    export default {
        props:['plans'],
        data() {
            return {
                stripeEmail: '',
                stripeToken: '',
                plan:1,
                status:false,
                coupon:''
            }
        },
        created() {
            this.stripe = StripeCheckout.configure({
                key: Laracast.stripeKey,
                image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                locale: 'auto',
                panelLabel:"Subscribe For",
                email:Laracast.user.email,
                token:  (token) => {
                    this.stripeEmail = token.email;
                    this.stripeToken = token.id;
                    axios.post('/subscriptions', this.$data)
                        .then(response => alert('Complete!'))
                        .catch(error =>{
                            this.status = error.response.data.status;
                        });

                }
            });
        },
        methods: {
            buy() {
                let plan = this.findPlanById(this.plan);
                this.stripe.open({
                    name: plan.name,
                    description: plan.name,
                    amount: plan.price
                });
            },
            findPlanById(id)
            {
                return this.plans.find(plan => plan.id == id);}
        }
    }
</script>
