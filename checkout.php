<?php 

    class Checkout {

        const SKU_ATV = 'atv';
        const SKU_IPD = 'ipd';
        const SKU_MBP = 'mbp';
        const SKU_VGA = 'vga';
        private $pricingRules = [];
        private $items = [];
        private $sub_totals = [];

        function __construct($pricingRules) {
            // Set pricing rules to array with 'sku' as key
            foreach($pricingRules as $pricingRule){
                $this->pricingRules[$pricingRule->sku] = $pricingRule;
            }
        }

        public function scan($item){
            $this->items[] = $item;
        }

        public function total(){
            $total = 0.00;
            $sub_totals = [];
            // Group item count by SKU
            $items_count = array_count_values($this->items);
            
            if(array_key_exists(self::SKU_ATV, $items_count) 
                ){
                $atv_count = $items_count[self::SKU_ATV];
                // Deduct one item with every 3 items found
                $atv_count = $atv_count - floor($atv_count/3);
                $atv_price = $this->pricingRules[self::SKU_ATV]->price;

                $sub_totals[self::SKU_ATV] = $atv_count * $atv_price;
                // print_r('atv: ' . $sub_totals[self::SKU_ATV] . '<br>');
            }

            if(array_key_exists(self::SKU_IPD, $items_count) 
                ){
                    $ipd_count = $items_count[self::SKU_IPD];
                    $ipd_price = $this->pricingRules[self::SKU_IPD]->price;
                    $ipd_discount_price = 499.99;

                    //Modify to discount price if more than 4 items found
                    if($ipd_count > 4)
                        $ipd_price = $ipd_discount_price;

                    $sub_totals[self::SKU_IPD] = $ipd_count * $ipd_price;
                    // print_r('ipd: ' . $sub_totals[self::SKU_IPD] . '<br>');
            }
            
            if(array_key_exists(self::SKU_MBP, $items_count) 
                ){
                    $mbp_count = $items_count[self::SKU_MBP];
                    $mbp_price = $this->pricingRules[self::SKU_MBP]->price;

                    $sub_totals[self::SKU_MBP] = $mbp_count * $mbp_price;
                    // print_r('mbp: ' . $sub_totals[self::SKU_MBP] . '<br>');
            }

            if(array_key_exists(self::SKU_VGA, $items_count) 
                ){
                    $vga_count = $items_count[self::SKU_VGA];
                    $vga_price = $this->pricingRules[self::SKU_VGA]->price;

                    if(array_key_exists(self::SKU_MBP, $items_count)){
                        $vga_count = $vga_count - $items_count[self::SKU_MBP];
                        // If vga items are less than mbp items, set vga items to zero
                        if($vga_count < 0)
                            $vga_count = 0;
                    }

                    $sub_totals[self::SKU_VGA] = $vga_count * $vga_price;
                    // print_r('vga: ' . $sub_totals[self::SKU_VGA] . '<br>');
            }

            $this->sub_totals = $sub_totals;
            $total = array_sum($sub_totals);
            // print_r($total . '<br>');
            return $total;
        }

        public function items(){
            return $this->items;
        }

        public function sub_totals(){
            return $this->sub_totals;
        }
    }

?>