// Updown_counter_tb.v ที่แก้ไข
// This is a testbench for the updown_counter module.

`timescale 1ns / 1ps

module updown_counter_tb;

    // Signal declarations for the testbench
    // reg for inputs, wire for outputs of the DUT
    reg clk, reset, load, en, up_down;
    reg [3:0] data;
    wire [3:0] count;

    // Instantiate the Design Under Test (DUT)
    // The name of the DUT is "my_counter"
    updown_counter #(.N(4)) my_counter (
        .clk(clk),
        .reset(reset),
        .load(load),
        .en(en),
        .up_down(up_down),
        .data(data),
        .count(count)
    );

    // Generate the clock signal
    // The clock is a continuous square wave with a 10ns period
    initial begin
        clk = 1'b0;
        forever #5 clk = ~clk; // Toggle clock every 5ns for a 10ns period
    end

    // Test stimulus generation
    // This initial block applies a sequence of inputs to test the counter's functionality
    initial begin
        // 1. Initial conditions and reset test
        $display("----------------------------------------------");
        $display("Running simulation for updown_counter...");
        $dumpfile("updown_counter.vcd"); // Specify the VCD file name
        $dumpvars(0, updown_counter_tb); // Dump all signals in the testbench

        reset = 1'b1;
        load = 1'b0;
        en = 1'b0;
        up_down = 1'b0;
        data = 4'b0000;
        #10; // Hold reset high for 10ns

        reset = 1'b0; // De-assert reset
        $display("Reset de-asserted. Starting counter test.");

        // 2. Count up test
        #10; // Wait for one clock period
        en = 1'b1;
        up_down = 1'b1;
        $display("Counting up...");
        #40; // Allow 4 clock cycles for counting

        // 3. Count down test
        #10;
        up_down = 1'b0;
        $display("Counting down...");
        #40; // Allow 4 clock cycles for counting

        // 4. Load test
        #10;
        load = 1'b1; // Assert load
        data = 4'd12; // Set data to 12
        en = 1'b0;
        $display("Loading value 12...");
        #10; // Wait for one clock period to see the load
        load = 1'b0;
        $display("Load de-asserted. Continuing count from 12.");

        // 5. Final count up from loaded value
        #10;
        en = 1'b1;
        up_down = 1'b1;
        #30; // Count up for a few cycles

        // 6. Hold test
        #10;
        en = 1'b0;
        $display("Holding value...");
        #30;

        // 7. Finish simulation
        $display("Simulation finished. Check updown_counter.vcd with GTKWave.");
        #10;
        $finish;
    end

endmodule

// โค้ดรันที่แก้ไข
// iverilog -o updown_counter_tb.vvp updown_counter.v updown_counter_tb.v