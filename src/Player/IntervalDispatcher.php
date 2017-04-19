<?php
/**
 * AppleRemoteCli - Apple Remote protocol console application
 *
 * @author  panlatent@gmail.com
 * @link    https://github.com/panlatent/apple-remote-cli
 * @license https://opensource.org/licenses/MIT
 */

namespace Panlatent\AppleRemoteCli\Player;

use Panlatent\AppleRemoteCli\Player\Ui\UiInterface;
use SplPriorityQueue;

class IntervalDispatcher
{
    protected $running = false;

    /**
     * @var int microsecond
     */
    protected $speed;

    protected $amend = 0;

    protected $queue;

    public function __construct($speed)
    {
        $this->speed = $speed;
        $this->queue = new SplPriorityQueue();
    }

    public function addTask(IntervalTaskInterface $task, $interval)
    {
        $this->queue->insert([$task, new Interval($interval)], $interval);
    }

    public function handle()
    {
        $this->running = true;
        while ($this->running) {
            $beforeTime = microtime(false);
            $queue = new SplPriorityQueue();
            foreach ($this->queue as $value) {
                list($task, $interval) = $value;
                /** @var \Panlatent\AppleRemoteCli\Player\IntervalTaskInterface $task */
                /** @var \Panlatent\AppleRemoteCli\Player\Interval $interval */
                if ($task->isHandle()) {
                    $beforeExecuteTime = microtime(false);
                    if ($interval->real >= $interval->fixed) {
                        $interval->fixed += $interval->interval;
                        $result = call_user_func([$task, 'handle']);
                        $executeTime = microtime(false) - $beforeExecuteTime;
                        if ($interval->fixed < 0) {
                            $interval->fixed += $interval->interval - $executeTime;
                        } else {
                            $interval->fixed = $interval->interval - $executeTime;
                        }
                        if ($result !== false) {
                            if (is_object($result) && $result instanceof UiInterface) {
                                $queue->insert([$result, $result->getRate(), 0], $result->getRate());
                                $result->show();
                            }
                            $interval->real = 0;
                            $queue->insert($value, $interval->interval);
                        } else {
                            if ($task instanceof UiInterface && $task->getParent()) {
                                /** @var \Panlatent\AppleRemoteCli\Player\Ui\UiInterface $parent */
                                $parent = $task->getParent();
                                $parent->show();
                                $queue->insert([$parent, new Interval($parent->getRate())], $parent->getRate());
                            }
                        }
                    } else {
                        $interval->real += $this->amend;
                        $queue->insert($value, $interval->interval);
                    }
                } else {
                    $queue->insert($value, $interval->interval);
                }
            }
            $amendTime = microtime(false) - $beforeTime;
            $this->queue = $queue;

            if ($this->speed >= $amendTime) {
                usleep($this->speed - $amendTime);
                $this->amend = $this->speed - $amendTime;
            } else {
                $this->amend = $amendTime;
            }
        }
    }
}