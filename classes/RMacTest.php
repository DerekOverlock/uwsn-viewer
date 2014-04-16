<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/NodeTest.php";
require_once PHP_LIB . "/Mail.php";

class RMacTest {
    /** @var NodeTest */
    private $test;
    private $traceFile = "test.tr";
    private $testFile = "test.tcl";
    private $testName = "test";
    private $namFile = "test.nam";
    private $resultFile = "test.out";
    private $largePacket = 480;

    public function __construct(NodeTest $test) {
        $this->testFile = TEST_DIR . "/test.tcl";
        $this->traceFile = TEST_DIR . "/test.tr";
        $this->test = $test;
    }

    public function getTestHeader() {
        $grid = $this->test->getGrid();
        $width = $grid->width;
        $height = $grid->height;
        $depth = $grid->depth;
        $numNodes = count($this->test->getTestNodes());
        $header = <<<HEADER
set opt(chan)		Channel/UnderwaterChannel
set opt(prop)		Propagation/UnderwaterPropagation
set opt(netif)		Phy/UnderwaterPhy
set opt(mac)		Mac/UnderwaterMac/RMac
set opt(ifq)		Queue/DropTail/PriQueue
set opt(ll)		LL
set opt(energy)         EnergyModel
set opt(txpower)        0.6
set opt(rxpower)        0.3
set opt(initialenergy)  10000
set opt(idlepower)      0.01
set opt(ant)            Antenna/OmniAntenna  ;#we don't use it in underwater
set opt(filters)        GradientFilter    ;# options can be one or more of
                                ;# TPP/OPP/Gear/Rmst/SourceRoute/Log/TagFilter



# the following parameters are set fot protocols
set opt(bit_rate)                     1.0e4
set opt(encoding_efficiency)          1
set opt(ND_window)                    1
set opt(ACKND_window)                 1
set opt(PhaseOne_window)              3
set opt(PhaseTwo_window)              1
set opt(PhaseTwo_interval)            0.5
set opt(IntervalPhase2Phase3)         1
set opt(duration)                     0.1
set opt(PhyOverhead)                  8
set opt(large_packet_size)            $this->largePacket; ;# 60 bytes
set opt(short_packet_size)            40  ;# 5 bytes
set opt(PhaseOne_cycle)               4 ;
set opt(PhaseTwo_cycle)               2 ;
set opt(PeriodInterval)               1
set opt(transmission_time_error)      0.0001;

set opt(dz)                           10
set opt(ifqlen)		              50	;# max packet in ifq
set opt(nn)	                	    $numNodes	;# number of nodes in each layer
set opt(layers)                         1
set opt(x)	                	    $width  ;# X dimension of the topography
set opt(y)	                        $height ;# Y dimension of the topography
set opt(z)                          $depth
set opt(seed)	                	348.88
set opt(stop)	                	1000	;# simulation time
set opt(prestop)                        20     ;# time to prepare to stop
set opt(tr)	                	"$this->traceFile"	;# trace file
set opt(nam)                    "$this->namFile"    ;# nam file
set opt(adhocRouting)                    Vectorbasedforward
set opt(width)                           20
set opt(adj)                             10
set opt(interval)                        0.001

# ==================================================================

LL set mindelay_		50us
LL set delay_			25us
LL set bandwidth_		0	;# not used

#Queue/DropTail/PriQueue set Prefer_Routing_Protocols    1

# unity gain, omni-directional antennas
# set up the antennas to be centered in the node and 1.5 meters above it
Antenna/OmniAntenna set X_ 0
Antenna/OmniAntenna set Y_ 0
Antenna/OmniAntenna set Z_ 1.5
Antenna/OmniAntenna set Z_ 0.05
Antenna/OmniAntenna set Gt_ 1.0
Antenna/OmniAntenna set Gr_ 1.0



Mac/UnderwaterMac set bit_rate_  \$opt(bit_rate)
Mac/UnderwaterMac set encoding_efficiency_  \$opt(encoding_efficiency)
Mac/UnderwaterMac/RMac set ND_window_  \$opt(ND_window)
Mac/UnderwaterMac/RMac set ACKND_window_ \$opt(ACKND_window)
Mac/UnderwaterMac/RMac set PhaseOne_window_ \$opt(PhaseOne_window)
Mac/UnderwaterMac/RMac set PhaseTwo_window_ \$opt(PhaseTwo_window)
Mac/UnderwaterMac/RMac set PhaseTwo_interval_ \$opt(PhaseTwo_interval)
Mac/UnderwaterMac/RMac set IntervalPhase2Phase3_ \$opt(IntervalPhase2Phase3)
#Mac/UnderwaterMac/RMac set ACKRevInterval_ 0.1
Mac/UnderwaterMac/RMac set duration_ \$opt(duration)
Mac/UnderwaterMac/RMac set PhyOverhead_ \$opt(PhyOverhead)
Mac/UnderwaterMac/RMac set large_packet_size_  \$opt(large_packet_size)
Mac/UnderwaterMac/RMac set short_packet_size_  \$opt(short_packet_size)
Mac/UnderwaterMac/RMac set PhaseOne_cycle_   \$opt(PhaseOne_cycle)
Mac/UnderwaterMac/RMac set PhaseTwo_cycle_   \$opt(PhaseTwo_cycle)
Mac/UnderwaterMac/RMac set PeriodInterval_   \$opt(PeriodInterval)
Mac/UnderwaterMac/RMac set transmission_time_error_ \$opt(transmission_time_error)




# Initialize the SharedMedia interface with parameters to make
# it work like the 914MHz Lucent WaveLAN DSSS radio interface
Phy/UnderwaterPhy set CPThresh_ 100  ;#10.0
Phy/UnderwaterPhy set CSThresh_ 0  ;#1.559e-11
Phy/UnderwaterPhy set RXThresh_ 0   ;#3.652e-10
#Phy/UnderwaterPhy set Rb_ 2*1e6
Phy/UnderwaterPhy set Pt_ 0.2818
Phy/UnderwaterPhy set freq_ 25  ;#frequency range in khz
Phy/UnderwaterPhy set K_ 2.0   ;#spherical spreading

# ==================================================================
# Main Program
# =================================================================

#
# Initialize Global Variables
#
#set sink_ 1


remove-all-packet-headers
#remove-packet-header AODV ARP TORA  IMEP TFRC
add-packet-header IP Mac LL  ARP  UWVB RMAC

set ns_ [new Simulator]
set topo  [new Topography]

\$topo load_cubicgrid \$opt(x) \$opt(y) \$opt(z)
set tracefd	[open \$opt(tr) w]
\$ns_ trace-all \$tracefd

set nf [open \$opt(nam) w]
\$ns_ namtrace-all-wireless \$nf \$opt(x) \$opt(y)


set phase1_time [expr \$opt(PhaseOne_cycle)*\$opt(PhaseOne_window)]
set phase2_time [expr \$opt(PhaseTwo_cycle)*(\$opt(PhaseTwo_window)+\$opt(PhaseTwo_interval))]


set start_time [expr \$phase1_time+\$phase2_time+\$opt(IntervalPhase2Phase3)]

puts "the start time is \$start_time"

set total_number [expr \$opt(nn)-1]
set god_ [create-god \$opt(nn)]

set chan_1_ [new \$opt(chan)]


global defaultRNG
\$defaultRNG seed \$opt(seed)

\$ns_ node-config -adhocRouting \$opt(adhocRouting) \
		 -llType \$opt(ll) \
		 -macType \$opt(mac) \
		 -ifqType \$opt(ifq) \
		 -ifqLen \$opt(ifqlen) \
		 -antType \$opt(ant) \
		 -propType \$opt(prop) \
		 -phyType \$opt(netif) \
		 #-channelType \$opt(chan) \
		 -agentTrace OFF \
                 -routerTrace OFF \
                 -macTrace ON \
                 -topoInstance \$topo\
                 -energyModel \$opt(energy)\
                 -txpower \$opt(txpower)\
                 -rxpower \$opt(rxpower)\
                 -initialEnergy \$opt(initialenergy)\
                 -idlePower \$opt(idlepower)\
                 -channel \$chan_1_
HEADER;
        return $header;
    }

    public function getTestBody() {
        ob_start();
        $i = 0;
        foreach($this->test->getTestNodes() as $testNode) {
?>

set node_(<?=$i;?>) [$ns_  node <?=$i;?>]
$node_(<?=$i;?>) set sinkStatus_ 1

$god_ new_node $node_(<?=$i;?>)
$node_(<?=$i;?>) set X_  <?=$testNode->x?>

$node_(<?=$i;?>) set Y_  <?=$testNode->y?>

$node_(<?=$i;?>) set Z_  <?=$testNode->z?>

<?php
if($i != 0) {
?>$node_(<?=$i;?>) set-cx <?=$testNode->x?>

$node_(<?=$i;?>) set-cy  <?=$testNode->y?>

$node_(<?=$i;?>) set-cz  <?=$testNode->z?>

$node_(<?=$i;?>) set_next_hop 0
<?php
} else {
?>$node_(<?=$i;?>) set passive 1
<?php
}
?>
set a_(<?=$i;?>) [new Agent/UWSink]
$ns_ attach-agent $node_(<?=$i;?>) $a_(<?=$i;?>)
$a_(<?=$i;?>) attach-vectorbasedforward $opt(width)
$a_(<?=$i;?>) cmd set-range 20
$a_(<?=$i;?>) cmd set-target-x <?=($i == 0 ? -20 : 0)?>

$a_(<?=$i;?>) cmd set-target-y <?=($i == 0 ? -10 : 0)?>

$a_(<?=$i;?>) cmd set-target-z <?=($i == 0 ? -20 : 0)?>

$a_(<?=$i;?>) set data_rate_ 0.05
<?php
            $i++;
        }
        return ob_get_clean();
    }

    public function getTestFooter() {
        ob_start();
        $i = 0;
        foreach($this->test->getTestNodes() as $testNode) {
            if($i != 0) {
?>
$ns_ at $start_time "$a_(<?=$i?>) exp-start"
<?php
            }
            $i++;
        }
?>

set node_size 10
for {set k 0} { $k<$opt(nn) } { incr k } {
    $ns_ initial_node_pos $node_($k) $node_size
}

puts "+++++++AFTER ANNOUNCE++++++++++++++"

<?php
        $i = 0;
        foreach($this->test->getTestNodes() as $testNode) {
            if($i == 0) $stopTime = '$opt(stop).001';
            else $stopTime = '$opt(stop).002';
?>
$ns_ at <?=$stopTime?> "$a_(<?=$i;?>) terminate"
<?php
            $i++;
        }
?>
$ns_ at $opt(stop).003  "$god_ compute_energy"
$ns_ at $opt(stop).004  "$ns_ nam-end-wireless $opt(stop)"
$ns_ at $opt(stop).005 "puts \"NS EXISTING...\"; $ns_ halt"

puts $tracefd "vectorbased"
puts $tracefd "M 0.0 nn $opt(nn) x $opt(x) y $opt(y) z $opt(z)"
puts $tracefd "M 0.0 prop $opt(prop) ant $opt(ant)"
puts "starting Simulation..."
$ns_ run
<?php
        return ob_get_clean();
    }

    public function writeTest() {
        $this->testName = self::getTestName();
        $this->testFile = TEST_DIR . "/" . $this->testName . ".tcl";
        $this->traceFile = TEST_DIR . "/" . $this->testName . ".tr";
        $this->namFile = TEST_DIR . "/" . $this->testName . ".nam";
        $fh = fopen($this->testFile, "w");
        fwrite($fh, $this->getTestHeader());
        fwrite($fh, "\n\n");
        fwrite($fh, $this->getTestBody());
        fwrite($fh, "\n\n");
        fwrite($fh, $this->getTestFooter());
        fclose($fh);
        chmod($this->testFile, 0666);
        return $this->testFile;
    }

    public function runTest() {
        $this->writeTest();
        $command = "ns " . $this->testFile;
        ob_start();
        $resultCode = system("ns " . $this->testFile);
        $result = ob_get_clean();
        $this->resultFile = TEST_DIR . "/" . $this->testName . ".out";
        file_put_contents($this->resultFile, $result);
        return new RMacTestResult($this->testFile, $this->traceFile, $this->namFile, $this->resultFile);
    }

    static private function getTestName($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        if(file_exists(TEST_DIR . "/" . $randomString . ".tcl")) return self::getTestName();
        else return $randomString;
    }
}

class RMacTestResult {
    public $tclFile;
    public $traceFile;
    public $namFile;
    public $resultFile;

    public function __construct($tclFile, $traceFile, $namFile, $resultFile) {
        $this->tclFile = $tclFile;
        $this->traceFile = $traceFile;
        $this->namFile = $namFile;
        $this->resultFile = $resultFile;
    }

    public function eraseResults() {
        unlink($this->tclFile);
        unlink($this->traceFile);
        unlink($this->namFile);
        unlink($this->resultFile);
    }

    public function toArray() {
        return array($this->tclFile, $this->resultFile, $this->traceFile, $this->namFile);
    }
}

class TraceFileParser {

    public $traceFile;
    /** @var resource */
    public $traceFh;

    public function __construct($traceFile) {
        $this->traceFile = $traceFile;
        $this->traceFh = fopen($this->traceFile, "r");
        if(!$this->traceFh) throw new Exception("Cannot open $this->traceFile");
    }

    /**
     * @return TracePacket[]
     */
    public function parse() {
        /** @var TracePacket[] $tracePackets */
        $tracePackets = array();
        /**
         * 1 - Trace Event Type
         * 2 - Time
         * 3 - Node ID
         * 4 - Level
         * 5 - Packet ID
         * 6 - Payload Type
         * 7 - Packet Size
         * 8 - MAC delay
         * 9 - Destination MAC address
         * 10 - Source MAC address
         * 11 - Ethernet Type Packet
         * 12 - energy
         * 13 - idle energy
         * 14 - sense energy
         * 15 - transmitting energy
         * 16 - receiving energy
         * 17 - source IP
         * 18 - source Port
         * 19 - destination IP
         * 20 - destination Port
         * 21 - TTL
         * 22 - Next Hop Addr
         */
        $pattern = "/^([a-zA-Z])\s+(\d+\.?\d*)\s+_(\d)_\s+(\w*)\s+---\s+(\d+)\s+(\w+)\s+(\d+)\s+\[(\d+)\s+(\d+)\s+([0-9a-fA-F]+)\s+(\d+)\]\s+\[energy\s+(\d+\.\d+)\s+ei\s+(\d+\.\d+)\s+es\s+(\d+\.\d+)\s+et\s+(\d+\.\d+)\s+er\s+(\d+\.\d+)\]\s+-------\s+\[(\d+):(\d+)\s+(\d+):(\d+)\s+(\d+)\s+(\d+)\]$/";
        while(($line = fgets($this->traceFh)) !== false) {
            $m = array();
            $line = trim($line);
            if($line[0] == "M" || $line[0] == "N" || $line[0] == "v") continue;
            preg_match($pattern, $line, $m);
            array_shift($m);
            if($m[6] <= 40) continue;
            $tracePackets[] = new TracePacket($m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21]);
        }
        return $tracePackets;
    }

}

class TracePacket {

    public $eventType;
    public $time;
    public $nodeId;
    public $level;
    public $packetId;
    public $payloadType;
    public $packetSize;
    public $macDelay;
    public $destMac;
    public $sourceMac;
    public $ethernetType;
    private $energy;
    private $idleEnergy;
    private $senseEnergy;
    private $transmitEnergy;
    private $receiveEnergy;
    public $sourceIp;
    public $sourcePort;
    public $destIp;
    public $destPort;
    public $ttl;
    public $nextHop;

    public function __construct($eventType, $time, $nodeId, $level, $packetId, $payloadType, $packetSize, $macDelay, $destMac, $sourceMac, $ethernetType, $energy, $idleEnergy, $senseEnergy, $transmitEnergy, $receiveEnergy, $sourceIp, $sourcePort, $destIp, $destPort, $ttl, $nextHop) {
        $this->eventType = $eventType;
        $this->time = $time;
        $this->nodeId = $nodeId;
        $this->level = $level;
        $this->packetId = $packetId;
        $this->payloadType = $payloadType;
        // in bytes
        $this->packetSize = $packetSize/8;
        $this->macDelay = $macDelay;
        $this->destMac = $destMac;
        $this->sourceMac = $sourceMac;
        $this->ethernetType = $ethernetType;
        $this->energy = $energy;
        $this->idleEnergy = $idleEnergy;
        $this->senseEnergy = $senseEnergy;
        $this->transmitEnergy = $transmitEnergy;
        $this->receiveEnergy = $receiveEnergy;
        $this->sourceIp = $sourceIp;
        $this->sourcePort = $sourcePort;
        $this->destIp = $destIp;
        $this->destPort = $destPort;
        $this->ttl = $ttl;
        $this->nextHop = $nextHop;

    }
}

class TracePacketTransaction {

    /** @var TracePacket */
    public $sentPacket = null;
    /** @var TracePacket  */
    public $receivePacket = "hi";
    public $transactionTime;
    public $rate;
    public $packetId;
    public $packetSize;
    public $numBytesSent = 0;
    public $numBytesReceived = 0;
    public $senderNode;

    public function addSentPacket(TracePacket $sent) {
        $this->sentPacket = $sent;
        $this->packetId = $this->sentPacket->packetId;
        $this->packetSize = $this->sentPacket->packetSize;
        $this->senderNode = $this->sentPacket->nodeId;
        $this->numBytesSent += $sent->packetSize;
    }

    public function addReceivePacket(TracePacket $receive) {
        $this->receivePacket = $receive;
        $this->numBytesReceived += $receive->packetSize;
        if($this->sentPacket != null) {
            $this->calculate();
        }
    }

    private function calculate() {
        $this->transactionTime = ($this->receivePacket->time-$this->sentPacket->time);
        $this->rate = $this->packetSize/$this->transactionTime / 1024;
    }

}

class RMacTestSuite {

    private $networkId;
    private $targetNodeId;
    /** @var RMacTest */
    private $test;
    /** @var RMacTestResult */
    private $testResults;
    /** @var TraceFileParser */
    private $parser;
    /** @var TracePacketTransaction[] */
    private $tracePackets;


    public function __construct($networkId, $targetNodeId) {
        $this->networkId = $networkId;
        $this->targetNodeId = $targetNodeId;
    }

    public function test() {
        $this->test = new RMacTest(new NodeTest($this->networkId, $this->targetNodeId));
        $this->testResults = $this->test->runTest();
        $this->parser = new TraceFileParser($this->testResults->traceFile);
        $packets = $this->parser->parse();
        foreach($packets as $packet) {
            $id = $packet->packetId;
            if(!isset($this->tracePackets[$id])) $this->tracePackets[$id] = new TracePacketTransaction();
            if($packet->eventType == 's') {
                $this->tracePackets[$id]->addSentPacket($packet);
            } else if($packet->eventType == 'r') {
                $this->tracePackets[$id]->addReceivePacket($packet);
            }
        }
    }

    /**
     * @return RMacTestResult
     */
    public function getTestResults() {
        return $this->testResults;
    }

    /**
     * @return TracePacketTransaction[]
     */
    public function getPacketTransactions() {
        return $this->tracePackets;
    }

    /**
     * @return RMacAggregateResult
     */
    public function aggregateTestResults() {
        $result = new RMacAggregateResult();
        foreach($this->tracePackets as $tracePacket) {
            $result->add($tracePacket);
        }
        return $result;
    }

}

class RMacAggregateResult {

    public $totalNumBytesSent = 0;
    public $totalNumBytesReceived = 0;
    public $totalTransactionTime = 0;
    public $numPackets = 0;
    public $avgRate = 0;

    private $totalRate = 0;


    public function __construct() {

    }

    public function add(TracePacketTransaction $packet) {
        $this->numPackets++;
        $this->totalNumBytesSent += $packet->numBytesSent;
        $this->totalNumBytesReceived += $packet->numBytesReceived;
        $this->totalTransactionTime += $packet->transactionTime;
        $this->totalRate += $packet->rate;
        $this->avgRate = $this->totalRate/$this->numPackets;
    }

    public function toString() {
        ob_start();
?># packets sent and received: <?=$this->numPackets;?> packets
Total bytes sent: <?=$this->totalNumBytesSent;?> bytes
Total bytes received: <?=$this->totalNumBytesReceived;?> bytes
Average throughput: <?=$this->avgRate?> kB/s
        <?php
        return ob_get_clean();
    }

}
