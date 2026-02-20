<?php

namespace Kompo\Elements\Traits;

trait HasAnimation
{
    /**
     * Set a transition animation with optional mode.
     *
     * Available transitions: fadeIn (default), fade, slideRight, slideLeft, slideUp, slideDown,
     * scale, scaleUp, elegant, magic, flip, blur, bounce, none.
     *
     * For Panels: controls both initial appearance AND content swap animations.
     *
     * @param string      $animation The transition name.
     * @param string|null $mode      The transition mode (optional). Default is simultaneous. Other choices: 'out-in' or 'in-out'
     *
     * @return self
     */
    public function animate($animation, $mode = '')
    {
        $this->config([
            'transition'     => $animation,
            'transitionMode' => $mode,
        ]);

        return $this;
    }

    /**
     * Set a transition name for smooth animations.
     * Alias for animate() without the mode parameter.
     *
     * @param string $transitionName The transition name (e.g., 'elegant', 'fade', 'slideUp')
     *
     * @return self
     */
    public function transition($transitionName)
    {
        return $this->animate($transitionName);
    }

    /**
     * Override the default duration for content swap animations (in milliseconds).
     *
     * @param int $durationMs Duration in milliseconds (e.g., 300, 500, 1000)
     *
     * @return self
     */
    public function transitionDuration($durationMs)
    {
        $this->config([
            'transitionDuration' => $durationMs,
        ]);

        return $this;
    }

    /**
     * Disable all transitions on this element.
     *
     * @return self
     */
    public function noTransition()
    {
        return $this->animate('none');
    }
}
