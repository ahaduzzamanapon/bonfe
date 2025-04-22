
 {{-- exam result modal --}}
<!-- Modal -->
<div class="modal fade" id="exam_result_modal" tabindex="-1" role="dialog" aria-labelledby="exam_result_modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Exam Result</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#exam_result_modal').modal('hide')">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="form-group">
                <label for="ExamResult_field">Exam Result</label>
                <select class="form-control" id="ExamResult_field">
                    <option value="Passed"> Passed </option>
                    <option value="Fail"> Fail </option>
                </select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="$('#exam_result_modal').modal('hide')" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submit_exam_result()">Save changes</button>
      </div>
    </div>
  </div>
</div>
    <script>
        function submit_exam_result() {
            const examResult = $('#ExamResult_field').val();
            const studentId = localStorage.getItem('student_id_for_exam_result');

            if (!examResult || !studentId) {
                alert('Please select a result and ensure a student ID is set');
                return false;
            }
            $.ajax({
                url: '{{ route('submit_exam_result') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    examResult,
                    studentId
                },
                success: function () {
                    alert('Result submitted successfully');
                    $('#exam_result_modal').modal('hide');
                    location.reload();
                },
                error: function () {
                    alert('Failed to submit exam result');
                }
            });
           
        }
    </script>
   {{-- exam result modal --}}
