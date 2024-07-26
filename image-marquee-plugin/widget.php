<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class My_Marquee_Widget extends \Elementor\Widget_Base {
    // Widget's name, title, icon, and category
    public function get_name() {
        return 'my_marquee_widget';
    }

    public function get_title() {
        return __('Marquee Widget', 'my-marquee-widget');
    }

    public function get_icon() {
        return 'eicon-slider-push'; // Use an appropriate Elementor icon
    }

    public function get_categories() {
        return ['general']; // Use an appropriate category
    }

    // Register widget controls
    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'my-marquee-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __('Image', 'my-marquee-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'images',
            [
                'label' => __('Images', 'my-marquee-widget'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'image' => ['url' => ''],
                    ],
                ],
                'title_field' => '{{{ image.url }}}',
            ]
        );

        $this->end_controls_section();

        // Add Style controls
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'my-marquee-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Add Slider controls for width and height
        $this->add_control(
            'image_width',
            [
                'label' => __('Image Width', 'my-marquee-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marquee-content li img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_height',
            [
                'label' => __('Image Height', 'my-marquee-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .marquee-content li img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    // Render widget output on the frontend
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="marquee">
          <ul class="marquee-content">
             <?php if (!empty($settings['images'])): ?>
                 <?php foreach ($settings['images'] as $image): ?>
                     <?php if (!empty($image['image']['url'])): ?>
                         <li><img src="<?php echo esc_url($image['image']['url']); ?>" alt="Image"></li>
                     <?php endif; ?>
                 <?php endforeach; ?>
             <?php endif; ?>
          </ul>
        </div>
        <style>
            :root {
                --marquee-width: 100vw;
                --marquee-height: auto;
                --marquee-elements-displayed: 5;
                --marquee-element-width: calc(var(--marquee-width) / var(--marquee-elements-displayed));
                --marquee-animation-duration: calc(var(--marquee-elements) * 3s);
            }
            
            .marquee {
                width: var(--marquee-width);
                height: var(--marquee-height);
                background-color: transparent;
                color: #eee;
                overflow: hidden;
                position: relative;
                padding: 0;
            }
            
            .marquee-content {
                list-style: none;
                height: 100%;
                display: flex;
                animation: scrolling var(--marquee-animation-duration) linear infinite;
            }
            
            @keyframes scrolling {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(-1 * var(--marquee-element-width) * var(--marquee-elements))); }
            }
            
            .marquee-content li {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-shrink: 0;
                width: var(--marquee-element-width);
                max-height: 100%;
                font-size: calc(var(--marquee-height)*3/4);
                white-space: nowrap;
            }
            
            .marquee-content li img {
                width: <?php echo esc_attr($settings['image_width']['size']); ?><?php echo esc_attr($settings['image_width']['unit']); ?>;
                height: <?php echo esc_attr($settings['image_height']['size']); ?><?php echo esc_attr($settings['image_height']['unit']); ?>;
                padding: 10px;
            }
            
            @media (max-width: 600px) {
                html { font-size: 12px; }
                :root {
                    --marquee-width: 100vw;
                    --marquee-height: auto;
                    --marquee-elements-displayed: 3;
                }
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const root = document.documentElement;
                const marqueeElementsDisplayed = getComputedStyle(root).getPropertyValue("--marquee-elements-displayed");
                const marqueeContent = document.querySelector("ul.marquee-content");

                root.style.setProperty("--marquee-elements", marqueeContent.children.length);

                for(let i=0; i<marqueeElementsDisplayed; i++) {
                    marqueeContent.appendChild(marqueeContent.children[i].cloneNode(true));
                }
            });
        </script>
        <?php
    }

    // Enqueue styles and scripts
//     protected function _enqueue_assets() {
//         wp_enqueue_style('my-marquee-widget-style', plugins_url('assets/css/marquee-style.css', __FILE__));
//         wp_enqueue_script('my-marquee-widget-script', plugins_url('assets/js/marquee-script.js', __FILE__), ['jquery'], null, true);
//     }
}
