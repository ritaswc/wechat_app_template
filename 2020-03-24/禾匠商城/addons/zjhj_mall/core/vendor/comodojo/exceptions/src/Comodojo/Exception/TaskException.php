<?php namespace Comodojo\Exception;

/**
 * TaskException handler; nothing special, just an implementation of
 * standard Exception class.
 *
 * @package     Comodojo Spare Parts
 * @author      Marco Giovinazzi <marco.giovinazzi@comodojo.org>
 * @license     MIT
 *
 * LICENSE:
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class TaskException extends \Exception {

    private $worklog_id = null;

    private $end_timestamp = null;

    public function __construct($message = null, $code = 0, \Exception $previous = null, $worklog_id = null) {
        
        $this->worklog_id = $worklog_id;

        $this->end_timestamp = microtime(true);

        parent::__construct($message, $code, $previous);

    }

    public function getWorklogId() {

        return $this->worklog_id;

    }

    public function getEndTimestamp() {

        return $this->end_timestamp;

    }

}