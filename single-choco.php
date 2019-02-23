<?php get_header(); ?>
<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main choco-main" role="main">
            <?php  while ( have_posts() ) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <?php //the_content(); ?>
            <?php endwhile; ?>
            <?php
            $k = $post->AirpressCollection[0];
            ?>
            <div class="temperature">
                <?php echo $k['Temperatur'];?>
            </div>
            <div class="table-top">
                <table>
                    <tr>
                        <th>Used before:</th>
                        <th>Manufacturing date:</th>
                        <th>Net o weight</th>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            $field_value = get_post_meta(get_the_ID(), 'used_before', true);
                            echo esc_html($field_value);
                            ?>
                        </td>
                        <td>
                            <?php
                            $field_second_value = get_post_meta(get_the_ID(), 'manufacturing_date', true);
                            echo esc_html($field_second_value);
                            ?>
                        </td>
                        <td>
                            <?php
                            $field_third_value = get_post_meta(get_the_ID(), 'net_o_weight', true);
                            echo esc_html($field_third_value);
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="ingredients"><?php echo $k['INGREDIENTSTEXT']; ?></div>
            <div class="choco-tables-bottom">
                <div class="weigth-table">
                    <table>
                        <tr>
                            <th>Näringsvärde per 100 g</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>Energi</td>
                            <td><?php echo $k['kj'].' kj/'.$k['kcal'].' kcal';?></td>
                        </tr>
                        <tr>
                            <td>Fet</td>
                            <td><?php echo $k[fat].' g'; ?></td>
                        </tr>
                        <tr>
                            <td>-varav mät at fet</td>
                            <td><?php echo $k['saturated fat'].' g';?></td>
                        </tr>
                        <tr>
                            <td>Kolhydrat</td>
                            <td><?php echo $k['carbo'].' g';?></td>
                        </tr>
                        <tr>
                            <td>- varav sockerarter</td>
                            <td><?php echo $k['where of sugar'].' g';?></td>
                        </tr>
                        <tr>
                            <td>Protein</td>
                            <td><?php echo $k['protein'].' g';?></td>
                        </tr>
                        <tr>
                            <td>Salt</td>
                            <td><?php echo $k['salt'].' g';?></td>
                        </tr>
                    </table>
                </div>
                <div class="barcode">
                    <div class="common-warning">
                        <?php echo $k['Common Warning'];?>
                    </div>
                    <img src="<?php print_r($k['Barcode'][0]['url']) ;?>" alt="">
                </div>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->
</div>
<?php get_footer();?>