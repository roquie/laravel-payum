<?php

use Illuminate\Http\Request;
use Mockery as m;
use Payum\Core\Model\Payment as PayumPayment;
use Payum\Core\Request\GetHumanStatus;
use Recca0120\LaravelPayum\Payment;
use Recca0120\LaravelPayum\Traits\DonePayment;

class DoneControllerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_done()
    {
        $payumToken = uniqid();
        $request = m::mock(Request::class);
        $payment = m::mock(Payment::class)
            ->shouldReceive('done')->with($request, $payumToken, m::type(Closure::class))->once()->andReturnUsing(function ($request, $payumToken, $closure) {
                $status = m::mock(GetHumanStatus::class);
                $payment = m::mock(PayumPayment::class);
                $token = m::mock(TokenInterface::class);
                $httpRequestVerifier = m::mock(HttpRequestVerifier::class);
                $gateway = m::mock(GatewayInterface::class);

                return $closure($status, $payment, $gateway, $token, $httpRequestVerifier);
            })
            ->mock();
        $controller = new DoneController();
        $controller->done($payment, $request, $payumToken);
    }
}

class DoneController
{
    use DonePayment;

    protected function onDone($payment, $status)
    {
    }
}
