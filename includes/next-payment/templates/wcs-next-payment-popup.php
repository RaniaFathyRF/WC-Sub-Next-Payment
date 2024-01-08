<div class="modal micromodal-slide wc-sub-box-next-payment-modal"
     data-subscription_id="<?php echo $subscription->get_id() ?>" id="wc_sub_box_next_payment_popup" aria-hidden="true">
	<div class="modal__overlay" tabindex="-1" data-micromodal-close>
		<div class="modal__container" role="dialog" aria-modal="true"
             aria-labelledby="modal_next_payment__popup_title">

            <header class="modal__header header-sticky">
                 <!-- Stylish circular container for the close button -->
                 <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                 <h2 class="modal__title" id="modal-1-title">
						<?php _e('Choose next payment date',  'wc-sub-next-payment'); ?>
                 </h2>
            </header>
            <main class="modal__content" id="modal-1-content">
                <!-- Body Section -->
                <div class="wc-sub-box-next-payment modal_body" >
                    <span class="wc-sub-box-next-payment next-payment-date"><?php echo "Choose next date: " ?></span>

    <input type="text" id="datepicker" class=" datepicker">
				</div>
            </main>


            <!-- Footer Section -->
				<footer class="wc-sub-box-next-payment modal__footer">
					<!-- Use data-micromodal-close to close the popup -->
                    <button class="modal__btn wp-element-button confirm-next-payment"><?php _e('Confirm', 'wc-sub-next-payment'); ?></button>
                    <button class="modal__btn wp-element-button" data-micromodal-close><?php _e('Cancel',  'wc-sub-next-payment'); ?></button>
				</footer>

				<!-- Add any additional content for your popup here -->
			</div>
		</div>
	</div>
