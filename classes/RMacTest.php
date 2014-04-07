<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/NodeTest.php";

class RMacTest {
    /** @var NodeTest */
    private $test;
    private $traceFile = "rmac.tr";
    private $testFile = "rmac.tcl";

    public function __construct(NodeTest $test) {
        $this->testFile = TEST_DIR . "/rmac.tcl";
        $this->traceFile = TEST_DIR . "/rmac.tr";
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
set opt(large_packet_size)            480 ;# 60 bytes
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
set opt(nam)                            "rmac.nam"  ;# nam file
set opt(adhocRouting)                    Vectorbasedforward
set opt(width)                           20
set opt(adj)                             10
set opt(interval)                        0.001
#set opt(traf)	                	"diffusion-traf.tcl"      ;# traffic file

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
        $this->testFile = self::getTestFilename();
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
        echo $command . "\n";
        ob_start();
        $resultCode = system("ns " . $this->testFile);
        $result = ob_get_clean();
        echo $resultCode . "\n";
        echo "Done running test. NS response: " . $resultCode . "\n";
        echo file_get_contents($this->traceFile);
        unlink($this->traceFile);
    }

    static private function getTestFilename() {
        $fileName = tempnam(TEST_DIR, "test_");
        return $fileName;
    }
}

header('Content-type: text/plain');
/*
$network = NodeNetwork::AddNetwork("Test");
Node::AddNode("Test1", 30.6343672, -88.04168701, -1, $network->getId());
Node::AddNode("Test2", 30.66035957, -88.01147461, -1, $network->getId());
Node::AddNode("Test4", 30.59654766, -87.97576904, -3, 1);
Node::AddNode("Test5", 37.10776507, 124.14550781, -70, 1);
*/

$t = new RMacTest(new NodeTest(1, 1));

$t->runTest();