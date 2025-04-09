<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

	$features             = array(
		'Support 30+ IDP',
		'SSO for Unlimited Users',
		'Easy Configuration',
		'Add SSO Login Button to Wp Login Page',
		'Links and ShortCodes for SSO',
	);
	$features_not_in_free = array(
		'Auto-sync IdP Configuration',
		'Customizable Login Button',
		'Advanced Attribute Mapping',
		'Advanced Role Mapping',
		'Protect/Hide WordPress Login',
		'Login/Logout Redirection',
		'Single Logout',
	);
	$premium_features     = array(
		'Support 30+ IDP',
		'SSO for Unlimited Users',
		'Easy Configuration',
		'Add SSO Login Button to Wp Login Page',
		'Links and ShortCodes for SSO',
		'Auto-sync IdP Configuration',
		'Customizable Login Button',
		'Advanced Attribute Mapping',
		'Advanced Role Mapping',
		'Protect/Hide WordPress Login',
		'Login/Logout Redirection',
		'Single Logout',
	);
	echo '
<!--  TABS CONTENT  -->

<div class="flex flex-col items-center bg-gradient-to-b from-kw-primary-bg to-kw-secondary-bg p-kw-6 text-center">
  <h1 class="text-kw-primary-txt text-lg m-kw-2 text-kw-head-text font-bold">Choose a plan that’s right for you</h1>
  <p class="text-kw-secondary-txt mb-kw-4 ">Explore our options and find the perfect plan for you. Attractively priced, our plans guarantee you get the best value for your investment.</p>
  <p class="text-kw-secondary-txt  mb-kw-8 font-semibold">Want a discount? Reach out to us at <a href="mailto:support@keywoot.com" class="text-kw-primary-txt text-blue-600 underline"><b>support@keywoot.com<b></a></p>

  <div class="flex w-full max-w-3xl gap-kw-8">
    <div class="bg-kw-primary-bg kw-price-card text-kw-primary-txt rounded-lg p-kw-6 shadow-md">
      <h2 class="mb-kw-2 text-lg text-kw-subtitle font-bold">Starter</h2>
      <p class="text-kw-secondary-txt mb-kw-4">Free, for trying things out. Free for stater</p>
      <p class="mb-kw-4 text-3xl font-bold">Free</p>
      <ul class="mb-kw-6 space-y-kw-2 text-left">';
	foreach ( $features as $feature ) {
		echo '  <li class="flex items-center"><span class="text-kw-app-primary mr-kw-2">✔️</span> ' . esc_attr( $feature ) . '</li>';
	}
	foreach ( $features_not_in_free as $feature ) {
		echo '<li class="flex items-center"><span class="text-muted-foreground mr-kw-2">❌</span>' . esc_attr( $feature ) . '</li>';
	}
	echo '
      </ul>
      <button class="bg-gray-100 text-muted-foreground w-full font-bold rounded-kw-smooth px-kw-4 py-kw-2">Current Plan</button>
    </div>

    <div class="bg-kw-primary-bg text-kw-primary-txt  relative rounded-lg p-kw-6 shadow-md    ">
      <h2 class="mb-kw-2 text-lg text-kw-subtitle font-bold">Premium</h2>
      <p class="text-kw-secondary-txt mb-kw-4">For you, your team & your Company</p>
      <p class="mb-kw-4 text-3xl font-bold">$199<span class="text-xs font-normal">/Per Year</span></p>
      <span class="bg-orange-600 text-white absolute right-kw-2 top-kw-4 rounded-full px-kw-3 py-kw-1 text-xs">Most Popular</span>
      <ul class="mb-kw-6 space-y-kw-2 text-left">';
	foreach ( $premium_features as $premium_feature ) {
		echo '           <li class="flex items-center"><span class="text-kw-app-primary mr-kw-2">✔️</span> ' . esc_attr( $premium_feature ) . '</li>';
	}
						echo '  
      </ul>
<button onclick="kwsso_upgrade_to_premium()" class="bg-kw-app-primary text-kw-light-ic w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:bg-gradient-to-l rounded-kw-smooth px-kw-4 py-kw-2">Upgrade To Premium</button>
    </div>


  </div>
</div>';
