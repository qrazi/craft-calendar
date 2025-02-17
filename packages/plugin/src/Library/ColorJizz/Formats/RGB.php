<?php

namespace Solspace\Calendar\Library\ColorJizz\Formats;

use Solspace\Calendar\Library\ColorJizz\ColorJizz;
use Solspace\Calendar\Library\ColorJizz\Exceptions\InvalidArgumentException;

/**
 * RGB represents the RGB color format.
 *
 * @author Mikee Franklin <mikeefranklin@gmail.com>
 */
class RGB extends ColorJizz
{
    /**
     * The red value (0-255).
     */
    public ?float $red = null;

    /**
     * The green value (0-255).
     */
    public ?float $green = null;

    /**
     * The blue value (0-255).
     */
    public ?float $blue = null;

    /**
     * Create a new RGB color.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(float $red, float $green, float $blue)
    {
        $this->toSelf = 'toRGB';

        if ($red < 0 || $red > 255) {
            throw new InvalidArgumentException(\sprintf('Parameter red out of range (%s)', $red));
        }
        if ($green < 0 || $green > 255) {
            throw new InvalidArgumentException(\sprintf('Parameter green out of range (%s)', $green));
        }
        if ($blue < 0 || $blue > 255) {
            throw new InvalidArgumentException(\sprintf('Parameter blue out of range (%s)', $blue));
        }

        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }

    /**
     * A string representation of this color in the current format.
     *
     * @return string The color in format: $red,$green,$blue (rounded)
     */
    public function __toString(): string
    {
        return $this->getRed().','.$this->getGreen().','.$this->getBlue();
    }

    /**
     * Get the red value (rounded).
     *
     * @return int The red value
     */
    public function getRed(): int
    {
        return (0.5 + $this->red) | 0;
    }

    /**
     * Get the green value (rounded).
     *
     * @return int The green value
     */
    public function getGreen(): int
    {
        return (0.5 + $this->green) | 0;
    }

    /**
     * Get the blue value (rounded).
     *
     * @return int The blue value
     */
    public function getBlue(): int
    {
        return (0.5 + $this->blue) | 0;
    }

    /**
     * Convert the color to Hex format.
     *
     * @return Hex the color in Hex format
     */
    public function toHex(): Hex
    {
        return new Hex($this->getRed() << 16 | $this->getGreen() << 8 | $this->getBlue());
    }

    /**
     * Convert the color to RGB format.
     *
     * @return RGB the color in RGB format
     */
    public function toRGB(): self
    {
        return $this;
    }

    /**
     * Convert the color to XYZ format.
     *
     * @return XYZ the color in XYZ format
     */
    public function toXYZ(): XYZ
    {
        $tmp_r = $this->red / 255;
        $tmp_g = $this->green / 255;
        $tmp_b = $this->blue / 255;
        if ($tmp_r > 0.04045) {
            $tmp_r = (($tmp_r + 0.055) / 1.055) ** 2.4;
        } else {
            $tmp_r /= 12.92;
        }
        if ($tmp_g > 0.04045) {
            $tmp_g = (($tmp_g + 0.055) / 1.055) ** 2.4;
        } else {
            $tmp_g /= 12.92;
        }
        if ($tmp_b > 0.04045) {
            $tmp_b = (($tmp_b + 0.055) / 1.055) ** 2.4;
        } else {
            $tmp_b /= 12.92;
        }
        $tmp_r *= 100;
        $tmp_g *= 100;
        $tmp_b *= 100;
        $new_x = $tmp_r * 0.4124 + $tmp_g * 0.3576 + $tmp_b * 0.1805;
        $new_y = $tmp_r * 0.2126 + $tmp_g * 0.7152 + $tmp_b * 0.0722;
        $new_z = $tmp_r * 0.0193 + $tmp_g * 0.1192 + $tmp_b * 0.9505;

        return new XYZ($new_x, $new_y, $new_z);
    }

    /**
     * Convert the color to Yxy format.
     *
     * @return Yxy the color in Yxy format
     */
    public function toYxy(): Yxy
    {
        return $this->toXYZ()->toYxy();
    }

    /**
     * Convert the color to HSV format.
     *
     * @return HSV the color in HSV format
     */
    public function toHSV(): HSV
    {
        $red = $this->red / 255;
        $green = $this->green / 255;
        $blue = $this->blue / 255;

        $min = min($red, $green, $blue);
        $max = max($red, $green, $blue);

        $value = $max;
        $delta = $max - $min;

        if (0 == $delta) {
            return new HSV(0, 0, $value * 100);
        }

        $saturation = 0;

        if (0 != $max) {
            $saturation = $delta / $max;
        } else {
            $saturation = 0;
            $hue = -1;

            return new HSV($hue, $saturation, $value);
        }
        if ($red == $max) {
            $hue = ($green - $blue) / $delta;
        } else {
            if ($green == $max) {
                $hue = 2 + ($blue - $red) / $delta;
            } else {
                $hue = 4 + ($red - $green) / $delta;
            }
        }
        $hue *= 60;
        if ($hue < 0) {
            $hue += 360;
        }

        return new HSV($hue, $saturation * 100, $value * 100);
    }

    /**
     * Convert the color to CMY format.
     *
     * @return CMY the color in CMY format
     */
    public function toCMY(): CMY
    {
        $cyan = 1 - ($this->red / 255);
        $magenta = 1 - ($this->green / 255);
        $yellow = 1 - ($this->blue / 255);

        return new CMY($cyan, $magenta, $yellow);
    }

    /**
     * Convert the color to CMYK format.
     *
     * @return CMYK the color in CMYK format
     */
    public function toCMYK(): CMYK
    {
        return $this->toCMY()->toCMYK();
    }

    /**
     * Convert the color to CIELab format.
     *
     * @return CIELab the color in CIELab format
     */
    public function toCIELab(): CIELab
    {
        return $this->toXYZ()->toCIELab();
    }

    /**
     * Convert the color to CIELCh format.
     *
     * @return CIELCh the color in CIELCh format
     */
    public function toCIELCh(): CIELCh
    {
        return $this->toCIELab()->toCIELCh();
    }
}
