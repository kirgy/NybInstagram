<?php
	/**
	*	Nybinstagram:Recursive Blocks
	*		A responsive and stylish recursive block template
	*		for Nybinstagram.
	*	------------------------------------------------------
	*	@author 	Christopher McKirgan, Nybblemouse
	*	date 		2014-07-22	
	*	@license 	MIT (open source, free to use and abuse)
	**/

?>
<div class="nybinstagram">
	<div class="size1of2 float">
		<div class="size1of1">
			<img src="<?php echo $this->data->selected{5}->thumbnail;?>" class="size1of4 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{5}->standard_resolution); ?>:/">
			<img src="<?php echo $this->data->selected{6}->thumbnail;?>" class="size1of4 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{6}->standard_resolution); ?>:/">
			<img src="<?php echo $this->data->selected{7}->thumbnail;?>" class="size1of4 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{7}->standard_resolution); ?>:/">
			<img src="<?php echo $this->data->selected{8}->thumbnail;?>" class="size1of4 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{8}->standard_resolution); ?>:/">
			<div class="clear"></div>
		</div>
		<div class="size3of4 float">
			<img src="<?php echo $this->data->selected{0}->thumbnail;?>" class="size1of1 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{0}->standard_resolution); ?>:/">
			<div class="clear"></div>
		</div>
		<div class="size1of4 float">
			<img src="<?php echo $this->data->selected{9}->thumbnail;?>" class="size1of1 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{9}->standard_resolution); ?>:/">
			<img src="<?php echo $this->data->selected{10}->thumbnail;?>" class="size1of1 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{10}->standard_resolution); ?>:/">
			<img src="<?php echo $this->data->selected{11}->thumbnail;?>" class="size1of1 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{11}->standard_resolution); ?>:/">
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="size1of2 float">
		<div class="size1of1">
			<img src="<?php echo $this->data->selected{1}->thumbnail;?>" class="size1of2 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{1}->standard_resolution); ?>:/">
			<img src="<?php echo $this->data->selected{2}->thumbnail;?>" class="size1of2 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{2}->standard_resolution); ?>:/">
			<div class="clear"></div>
		</div>
		<div class="size1of1">
			<img src="<?php echo $this->data->selected{3}->thumbnail;?>" class="size1of2 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{3}->standard_resolution); ?>:/">
			<img src="<?php echo $this->data->selected{4}->thumbnail;?>" class="size1of2 image ajax-block" dataset="nybinstagram:<?php echo urlencode($this->data->selected{4}->standard_resolution); ?>:/">
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>