import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-show-question',
  templateUrl: './show-question.page.html',
  styleUrls: ['./show-question.page.scss'],
})
export class ShowQuestionPage implements OnInit {

  code = `
  <p>Here is a piece of C++ code that shows some very peculiar behavior. For some strange reason, sorting the data miraculously makes the code almost six times faster:</p><p><br></p><pre class="ql-syntax" spellcheck="false">#include &lt;algorithm&gt;
#include &lt;ctime&gt;
#include &lt;iostream&gt;

int main()
{
&nbsp;&nbsp;// Generate data
&nbsp;&nbsp;const unsigned arraySize = 32768;
&nbsp;&nbsp;int data[arraySize];

&nbsp;&nbsp;for (unsigned c = 0; c &lt; arraySize; ++c)
&nbsp;&nbsp;&nbsp;&nbsp;data[c] = std::rand() % 256;

&nbsp;&nbsp;// !!! With this, the next loop runs faster.
&nbsp;&nbsp;std::sort(data, data + arraySize);

&nbsp;&nbsp;// Test
&nbsp;&nbsp;clock_t start = clock();
&nbsp;&nbsp;long long sum = 0;

&nbsp;&nbsp;for (unsigned i = 0; i &lt; 100000; ++i)
&nbsp;&nbsp;{
&nbsp;&nbsp;&nbsp;&nbsp;// Primary loop
&nbsp;&nbsp;&nbsp;&nbsp;for (unsigned c = 0; c &lt; arraySize; ++c)
&nbsp;&nbsp;&nbsp;&nbsp;{
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if (data[c] &gt;= 128)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sum += data[c];
&nbsp;&nbsp;&nbsp;&nbsp;}
&nbsp;&nbsp;}

&nbsp;&nbsp;double elapsedTime = static_cast&lt;double&gt;(clock() - start) / CLOCKS_PER_SEC;

&nbsp;&nbsp;std::cout &lt;&lt; elapsedTime &lt;&lt; std::endl;
&nbsp;&nbsp;std::cout &lt;&lt; "sum = " &lt;&lt; sum &lt;&lt; std::endl;
}
</pre><ul><li>Without std::sort(data, data + arraySize);, the code runs in 11.54 seconds.</li><li>With the sorted data, the code runs in 1.93 seconds.</li></ul><p><br></p><p>Initially, I thought this might be just a language or compiler anomaly, so I tried Java:</p><p><br></p><pre class="ql-syntax" spellcheck="false"><span class="hljs-keyword">import</span> java.util.Arrays;
<span class="hljs-keyword">import</span> java.util.Random;

public <span class="hljs-class"><span class="hljs-keyword">class</span> <span class="hljs-title">Main</span>
</span>{
&nbsp;&nbsp;public <span class="hljs-keyword">static</span> <span class="hljs-keyword">void</span> main(<span class="hljs-built_in">String</span>[] args)
&nbsp;&nbsp;{
&nbsp;&nbsp;&nbsp;&nbsp;<span class="hljs-comment">// Generate data</span>
&nbsp;&nbsp;&nbsp;&nbsp;int arraySize = <span class="hljs-number">32768</span>;
&nbsp;&nbsp;&nbsp;&nbsp;int data[] = <span class="hljs-keyword">new</span> int[arraySize];

&nbsp;&nbsp;&nbsp;&nbsp;Random rnd = <span class="hljs-keyword">new</span> Random(<span class="hljs-number">0</span>);
&nbsp;&nbsp;&nbsp;&nbsp;<span class="hljs-keyword">for</span> (int c = <span class="hljs-number">0</span>; c &lt; arraySize; ++c)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;data[c] = rnd.nextInt() % <span class="hljs-number">256</span>;

&nbsp;&nbsp;&nbsp;&nbsp;<span class="hljs-comment">// !!! With this, the next loop runs faster</span>
&nbsp;&nbsp;&nbsp;&nbsp;Arrays.sort(data);

&nbsp;&nbsp;&nbsp;&nbsp;<span class="hljs-comment">// Test</span>
&nbsp;&nbsp;&nbsp;&nbsp;long start = System.nanoTime();
&nbsp;&nbsp;&nbsp;&nbsp;long sum = <span class="hljs-number">0</span>;

&nbsp;&nbsp;&nbsp;&nbsp;<span class="hljs-keyword">for</span> (int i = <span class="hljs-number">0</span>; i &lt; <span class="hljs-number">100000</span>; ++i)
&nbsp;&nbsp;&nbsp;&nbsp;{
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="hljs-comment">// Primary loop</span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="hljs-keyword">for</span> (int c = <span class="hljs-number">0</span>; c &lt; arraySize; ++c)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="hljs-keyword">if</span> (data[c] &gt;= <span class="hljs-number">128</span>)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sum += data[c];
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
&nbsp;&nbsp;&nbsp;&nbsp;}

&nbsp;&nbsp;&nbsp;&nbsp;System.out.println((System.nanoTime() - start) / <span class="hljs-number">1000000000.0</span>);
&nbsp;&nbsp;&nbsp;&nbsp;System.out.println(<span class="hljs-string">"sum = "</span> + sum);
&nbsp;&nbsp;}
}
</pre><p>With a similar but less extreme result.</p><p><br></p><p>My first thought was that sorting brings the data into the cache, but then I thought how silly that was because the array was just generated.</p><p><br></p><ul><li>What is going on?</li><li>Why is processing a sorted array faster than processing an unsorted array?</li></ul><p>The code is summing up some independent terms, so the order should not matter.</p>
  `
  htmlText =""
  constructor() { }

  ngOnInit() {
  }

}
