This program is written in python2 and is configured to use a FTDI USB to 
Serial Adapter.  I have tested this on Linux systems and the on BeagleBone 
Black computer.

For the BealeBone Black,  I've included a simple install script that will
install any package dependencies.

There are no variable format checks at this time.  When prompted for values,
pay close attention to the format of the data.  For example:


    Frequencies should be entered as XXX.YYYY using the full four digits.
    CTSS/DCS values should be entered with four digits, 0022 or 0754N. See
    below for more details.


Standard CTCSS Tones:

      Motorola  RELM	|             Motorola 	RELM
Tone	Code	Code 	|	Tone	Code 	Code
================================================================
None	None	000	|	167.9	6Z	33
67.0	XZ	001	|	173.8	6A	34
71.9	XA	002	|	179.9	6B	35
74.4	WA	003	|	186.2	7Z	36
77.0	XB	004	|	192.8	7A	37
79.7	WB	005	|	203.5	M1	38
82.5	YZ	006	|	210.7	M2	none
85.4	YA	007	|	218.1	M3	none
88.5	YB	008	|	225.7	M4	none
91.5	ZZ	009	|	233.6	none	none
94.8	ZA	010	|	241.8	none	none
97.4	ZB	011	|	250.3	none	none
100.0	1Z	012	|	69.4	WZ	none
103.5	1A	013	|	159.8	none	none
107.2	1B	014	|	165.5	none	none
110.9	2Z	015	|	171.3	none	none
114.8	2A	016	|	177.3	none	none
118.8	2B	017	|	183.5	none	none
123.0	3Z	018	|	189.9	none	none
127.3	3A	019	|	196.6	none	none
131.8	3B	020	|	199.5	none	none
136.5	4Z	021	|	206.5	8Z	none
141.3	4A	022	|	229.1	9Z	none
146.2	4B	023	|	254.1	0Z	none
151.4	5Z	024	|	150.0	none	none 
156.7	5A	025	|				
162.2	5B	026	|	

The  CTCSS tones should be entered using all for digits, for example CTCCS
tone 181.8, should be entered as 0020.


DCS Codes:

CDCSS or Digital Code Squelch (DCS) is a further development of the continuous 
tone-coded squelch system or CTCSS that uses a slow-speed, binary data 
stream passed as sub-audible data along with the transmission. Motorola 
calls this Digital Private Line or DPL. It consists of a 23-bit telegram 
sent repeatedly on the channel at 134 bits per second along with the voice 
transmission. This allows for over 100 possible fleet codes to be used. 
This gives it an advantage over the CTCSS tones in that there are more 
possible codes to use; however, it does use more bandwidth and can be 
affected by voice tones below 300 Hz if not properly filtered by the 
radio circuitry. Below is a chart of the DCS codes:

6	50	125	174	255	343	445	526	703
7	51	131	205	261	346	446	532	712
15	53	132	212	263	351	452	546	723
17	54	134	214	265	356	454	565	731
21	65	141	223	266	364	455	606	732
23	71	143	225	271	365	462	612	734
25	72	145	226	274	371	464	624	743
26	73	152	243	306	411	465	627	754
31	74	155	244	311	412	466	631
32	114	156	245	315	413	503	632
36	115	162	246	325	423	506	654
43	116	165	251	331	431	516	662
47	122	172	252	332	432	523	664

The format needs to be full three digets plus an "N". For example, DCS code 7 
should be entered as : 007N



Inverted Codes

The format for inverted codes is the same as DCS codes except instead of an "N"
you change it to "I".  For example, DCS code 7 inverted, would be entered as:
007I.



$Id: README.txt 3 2014-12-03 01:04:43Z w0anm $


