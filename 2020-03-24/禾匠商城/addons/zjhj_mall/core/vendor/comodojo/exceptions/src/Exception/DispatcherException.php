<?php namespace Comodojo\Exception;

/**
 * DispatcherException handler; nothing special, just an implementation of
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

class DispatcherException extends \Exception {

    private $status;

    private $headers = array();

    public function __construct($message = null, $code = 0, \Exception $previous = null, $status = null, $headers = array()) {

        $this->status = empty($status) ? 500 : $status;

        parent::__construct($message, $code, $previous);

    }

    public function getStatus() {

        return $this->status;

    }

    public function getHeaders() {

        return $this->headers;

    }

}
