@extends('welcome')
@section('title') Stripe Integration Example @endsection
@section('css')
<style>
/**
 * The CSS shown here will not be introduced in the Quickstart guide, but shows
 * how you can use CSS to style your Element's container.
 */
.StripeElement {
  box-sizing: border-box;

  height: 50px;
  border-radius: 5px;

  padding: 14px 12px;
  padding-left: 10px;

  border: 1px solid #2d4186;
  background: transparent;

  font-family: inherit;
  font-size: 100%;
}


#card-errors{
    color: #fa755a;
}

  /*box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}*/
.StripeElement--focus {
  border: 1px solid teal;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}


/* Input Element */

#name_on_card {
  box-sizing: border-box;

  height: 50px;
  border-radius: 5px;

  padding: 14px 12px;
  padding-left: 10px;

  border: 1px solid #2d4186;
  background: transparent;

  font-family: inherit;
  font-size: 100%;
}

#name_on_card--focus {
  border: 1px solid teal;
}

#name_on_card--invalid {
  border-color: #fa755a;
}

#name_on_card--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>
@endsection
@section('content')

<h2 class="text-2xl md:text-4xl font-sans text-center font-bold">Stripe Integration Example</h2>
<p class="text-sm font-sans text-center pt-2 pb-10 font-medium text-gray-400 mx-4"><b class="text-black"> Note: </b> This is just a demo, so we used limited & static data.</p>

@if (session()->has('success_message'))

            <div class="text-blue-300 mt-4">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)

            <div class="text-red-600 mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
<div class="leading-loose">
  <form action="{{ route('stripe.store') }}" method="POST" id="payment-form" class="max-w-xl m-2 p-10 bg-white rounded shadow-xl">
    @csrf
    <p class="text-gray-800 font-medium">Payment information</p>
    <div class="my-4">
      <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" type="text" required placeholder="Name on card" aria-label="Name" id="name_on_card" name="name_on_card">
    </div>

    <!-- Stripe Element -->
    <div class="mt-6 mb-5">
      <div id="card-element">
        <!-- A Stripe Element will be inserted here. -->
      </div>
      <div id="card-errors" role="alert"></div>
    </div>
    <!-- End Stripe Element -->

    <div class="mt-4">
      <button class="px-4 py-1 text-white font-light tracking-wider bg-gray-900 rounded" id="complete-order" value="make payment" type="submit">$1.00</button>
    </div>
  </form>
</div>

@include('extra')

@endsection


@section('js')
    <script src="https://js.stripe.com/v3/"></script>

<script>
    // Create a Stripe client.
var stripe = Stripe('pk_test_WXlUk1WnVatPBW5fIXWh1taU00MZfrLpt2');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
  base: {
    color: '#6845e0',
    fontFamily: 'inherit',
    fontSmoothing: 'antialiased',
    fontSize: '17px',
    '::placeholder': {
      color: '#dbe2fb'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
        displayError.textContent = event.error.message;
        } else {
        displayError.textContent = '';
        }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Disable the submit button to prevent repeated clicks
            document.getElementById('complete-order').disabled = true;

            // Integrating with our checkout form fields
            var options = {
                name: document.getElementById('name_on_card').value,
              //  address_line1: document.getElementById('address').value,
            //    address_city: document.getElementById('city').value,
             //   address_state: document.getElementById('province').value,
              //  address_zip: document.getElementById('postalcode').value
            }

        stripe.createToken(card, options).then(function(result) {
        if (result.error) {
        // Inform the user if there was an error.
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = result.error.message;

            // Enable the submit button
            document.getElementById('complete-order').disabled = false;

        } else {
        // Send the token to your server.
        stripeTokenHandler(result.token);
        }
        });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
        }
</script>
@endsection