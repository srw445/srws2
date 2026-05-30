using System.Globalization;
using System.Text.RegularExpressions;
using System.Windows;
using System.Windows.Input;

namespace WpfApp1
{
    public partial class MainWindow : Window
    {
        private readonly char _decimalSeparator;
        private readonly char _groupSeparator;

        public MainWindow()
        {
            InitializeComponent();

            var nfi = CultureInfo.CurrentCulture.NumberFormat;
            _decimalSeparator = nfi.NumberDecimalSeparator.Length > 0 ? nfi.NumberDecimalSeparator[0] : '.';
            _groupSeparator = nfi.NumberGroupSeparator.Length > 0 ? nfi.NumberGroupSeparator[0] : ',';

            Loaded += (_, __) => Number1TextBox.Focus();
        }

        private void AddButton_Click(object sender, RoutedEventArgs e)
        {   
            AddNumbers();
        }

        private void ClearButton_Click(object sender, RoutedEventArgs e)
        {
            Number1TextBox.Clear();
            Number2TextBox.Clear();
            ResultTextBlock.Text = "結果: ";
            Number1TextBox.Focus();
        }

        private void NumberTextBox_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                AddNumbers();
                e.Handled = true;
            }
        }

        // 簡易入力フィルタ（数字・マイナス・現在カルチャの小数区切り・グループ区切りを許可）
        private void Number_PreviewTextInput(object sender, TextCompositionEventArgs e)
        {
            var text = e.Text;
            if (string.IsNullOrEmpty(text))
            {
                e.Handled = false;
                return;
            }

            // 数字
            if (Regex.IsMatch(text, @"^\d+$"))
            {
                e.Handled = false;
                return;
            }

            // マイナス記号（複数や途中を完全に弾く実装はTryParseで最終判定）
            if (text == "-")
            {
                e.Handled = false;
                return;
            }

            // 小数点・桁区切り
            if (text.Length == 1 && (text[0] == _decimalSeparator || text[0] == _groupSeparator))
            {
                e.Handled = false;
                return;
            }

            e.Handled = true;
        }

        private void AddNumbers()
        {
            if (!TryParseFrom(Number1TextBox.Text, out double a))
            {
                ResultTextBlock.Text = "数1 の入力が正しくありません。";
                Number1TextBox.Focus();
                return;
            }

            if (!TryParseFrom(Number2TextBox.Text, out double b))
            {
                ResultTextBlock.Text = "数2 の入力が正しくありません。";
                Number2TextBox.Focus();
                return;
            }

            var sum = a + b;
            ResultTextBlock.Text = $"結果: {sum.ToString("G", CultureInfo.CurrentCulture)}";
        }

        private bool TryParseFrom(string text, out double value)
        {
            value = 0;

            if (string.IsNullOrWhiteSpace(text))
            {
                value = 0;
                return true;
            }

            // カルチャを使ってパース（小数区切りや桁区切りを尊重）
            return double.TryParse(text, NumberStyles.Float | NumberStyles.AllowThousands, CultureInfo.CurrentCulture, out value);
        }
    }
}