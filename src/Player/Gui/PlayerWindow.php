<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player\Gui;

use Gui\Application as Gui;
use Gui\Components\Button;
use Gui\Components\Label;
use Panlatent\AppleRemoteCli\Ui\View;

/**
 * Class PlayerWindow
 *
 * @package Panlatent\AppleRemoteCli\Player\Gui
 *
 */
class PlayerWindow extends View
{
    /**
     * @var \Panlatent\AppleRemoteCli\Player\PlayerViewModel
     */
    protected $viewModel;

    /**
     * @var Label
     */
    protected $songNameLabel;

    /**
     * @var Label
     */
    protected $songArtistLabel;

    public function init(Gui $gui)
    {
        $window = $gui->getWindow();
        $window->setTitle('Apple Remote');
        $window->setWidth(300);
        $window->setHeight(500);

        $this->songNameLabel = new Label();
        $this->songNameLabel->setLeft(0)->setTop(0)->setWidth(300)->setHeight(30);

        $this->songArtistLabel = new Label();
        $this->songArtistLabel->setLeft(0)->setTop(30)->setWidth(300)->setHeight(30);

        $button = new Button();
        $button->setLeft(40)->setTop(100)->setWidth(200);
        $button->setValue('Apple Remote GUI for PHP!');
        $button->on('click', function() use ($button) {
            $button->setValue($this->viewModel->songName);
        });
    }

    public function handle()
    {
        $this->render();
    }

    public function render()
    {
        $this->update();
    }

    protected function update()
    {
        $this->songNameLabel->setText($this->viewModel->songName);
        $this->songArtistLabel->setText($this->viewModel->songArtist . ' - ' . $this->viewModel->songAlbum);
    }
}