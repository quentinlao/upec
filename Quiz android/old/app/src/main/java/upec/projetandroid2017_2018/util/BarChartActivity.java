package upec.projetandroid2017_2018.util;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.widget.LinearLayout;

import com.github.mikephil.charting.charts.BarChart;
import com.github.mikephil.charting.components.AxisBase;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.components.YAxis;
import com.github.mikephil.charting.data.BarData;
import com.github.mikephil.charting.data.BarDataSet;
import com.github.mikephil.charting.data.BarEntry;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.formatter.IAxisValueFormatter;
import com.github.mikephil.charting.formatter.IValueFormatter;
import com.github.mikephil.charting.utils.ColorTemplate;
import com.github.mikephil.charting.utils.ViewPortHandler;

import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.List;

import upec.projetandroid2017_2018.R;

/**
 * Created by Quentin on 14/03/2018.
 */
class MyValueFormatter implements IValueFormatter {

    private DecimalFormat mFormat;

    public MyValueFormatter() {
        mFormat = new DecimalFormat("0");
    }

    @Override
    public String getFormattedValue(float value, Entry entry, int dataSetIndex, ViewPortHandler viewPortHandler) {
        return mFormat.format(value);
    }
}
public class BarChartActivity extends AppCompatActivity {
    @Override
    protected void onCreate( Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_barchart);
        int a = getIntent().getIntExtra("a",0);
        int b = getIntent().getIntExtra("b",0);
        int c = getIntent().getIntExtra("c",0);
        int d = getIntent().getIntExtra("d",0);
        createBarChart(a,b,c,d);
    }
    void createBarChart(int a, int b, int c, int d){
          /*CREATION DU BARCHART */
        BarChart chart = new BarChart(this);
        chart.getDescription().setText("Choix réponess");
        chart.getLegend().setEnabled(false);
        setContentView(chart);
        /*VALEURS */
        final List<BarEntry> entries = new ArrayList<>();
        entries.add(new BarEntry(0f, a));
        entries.add(new BarEntry(1f, b));
        entries.add(new BarEntry(2f, c));
        entries.add(new BarEntry(3f, d));


        BarDataSet dataSet = new BarDataSet(entries, "val");
        dataSet.setColors(ColorTemplate.VORDIPLOM_COLORS);
        dataSet.setValueTextSize(20f);

        /*AXE X */
        final String[] quarters = new String[] { "A", "B", "C", "D" };
        IAxisValueFormatter formatter = new IAxisValueFormatter() {
            @Override
            public String getFormattedValue(float value, AxisBase axis) {
                return quarters[(int) value];
            }
        };
        XAxis xAxis = chart.getXAxis();
        xAxis.setGranularity(1.0f);
        xAxis.setValueFormatter(formatter);


        /*AXE GAUCHE DROITE Y */
        YAxis left = chart.getAxisLeft();
        left.setDrawLabels(false);
        left.setDrawAxisLine(false);
        left.setDrawGridLines(false);
        left.setDrawZeroLine(true);
        chart.getAxisRight().setEnabled(false);

        /*PARAMETRES*/
        dataSet.setValueFormatter(new MyValueFormatter());
        BarData data = new BarData(dataSet);
        data.setBarWidth(0.9f);
        chart.setData(data);
        chart.setFitBars(true);
        chart.invalidate();

    }
}
